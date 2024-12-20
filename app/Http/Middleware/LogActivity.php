<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\Project;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\EstimatesInvoice;

class LogActivity
{
    protected $operationMap = [
        'store' => 'created',
        'update' => 'updated',
        'duplicate' => 'duplicated',
        'destroy' => 'deleted',
        'destroy_multiple' => 'deleted',
        'updatestatus' => 'updated_status',
        'update_status' => 'updated_status',
        'update_user' => 'updated',
        'delete_user' => 'deleted',
        'delete_multiple_user' => 'deleted',
        'create_sign' => 'signed',
        'delete_sign' => 'unsigned',
        'store_contract_type' => 'created',
        'update_contract_type' => 'updated',
        'delete_contract_type' => 'deleted',
        'delete_multiple_contract_type' => 'deleted',
        'upload_media' => 'uploaded',
        'delete_media' => 'deleted',
        'delete_multiple_media' => 'deleted',
        'store_expense_type' => 'created',
        'update_expense_type' => 'updated',
        'delete_expense_type' => 'deleted',
        'delete_multiple_expense_type' => 'deleted',
        'store_milestone' => 'created',
        'update_milestone' => 'updated',
        'delete_milestone' => 'deleted',
        'delete_multiple_milestone' => 'deleted',

    ];

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if the request was successful (status code 2xx) and contains the expected JSON structure
        if ($response->isSuccessful()) {
            $responseData = json_decode($response->getContent(), true);
            // dd($responseData);
            // Check for the expected 'error' => false and the presence of 'id' key
            if (isset($responseData['error']) && $responseData['error'] === false && isset($responseData['id'])) {
                $routeAction = $request->route()->getAction();

                // Extracting controller and method from route action
                $controllerAction = explode('@', $routeAction['controller']);
                $controller = $controllerAction[0];
                $method = $controllerAction[1];

                // Fetching type and operation from the controller and method
                $segments = explode('\\', $controller);
                $type = isset($responseData['type']) ? $responseData['type'] : Str::singular(strtolower(str_replace('Controller', '', end($segments))));
                $operation = isset($responseData['operation']) ? $responseData['operation'] : strtolower($method);
                $operation = $this->convertOperation($operation);

                $workspace_id = session()->get('workspace_id');
                $actor_id = getAuthenticatedUser()->id;
                $actor_type = Auth::guard('web')->check() ? 'user' : 'client';
                $actorName = getAuthenticatedUser()->first_name . ' ' . getAuthenticatedUser()->last_name;
                // Log the activity
                if (is_array($responseData['id'])) {
                    $ids = $responseData['id'];
                    $parentIds = isset($responseData['parent_id']) && is_array($responseData['parent_id']) ? $responseData['parent_id'] : [];

                    foreach ($ids as $key => $id) {
                        // Attempt to get the title from the response, fallback to fetching from the database
                        $title = isset($responseData['titles'][$key]) ? $responseData['titles'][$key] : $this->getTitleForType($type, $id);
                        $message = isset($responseData['activity_message']) ? $responseData['activity_message'] : $actorName . ' ' . $operation . ' ' . strtolower(str_replace('_', ' ', $type)) . ' ' . $title;
                        $logData = [
                            'workspace_id' => $workspace_id,
                            'actor_id' => $actor_id,
                            'actor_type' => $actor_type,
                            'type_id' => $id,
                            'type' => $type,
                            'activity' => $operation,
                            'message' => $message,
                        ];

                        // Check if 'parent_id' is set in $responseData and is an array
                        if (isset($parentIds[$key])) {
                            $logData['parent_type_id'] = $parentIds[$key];
                        }

                        if (isset($responseData['parent_type'])) {
                            $logData['parent_type'] = $responseData['parent_type'];
                        }

                        ActivityLog::create($logData);
                    }
                } else {
                    // Log the activity for a single $type_id
                    // Attempt to get the title from the response, fallback to fetching from the database
                    $title = isset($responseData['title']) ? $responseData['title'] : $this->getTitleForType($type, $responseData['id']);
                    $message = isset($responseData['activity_message']) ? $responseData['activity_message'] : $actorName . ' ' . $operation . ' ' . strtolower(str_replace('_', ' ', $type)) . ' ' . $title;

                    $logData = [
                        'workspace_id' => $workspace_id,
                        'actor_id' => $actor_id,
                        'actor_type' => $actor_type,
                        'type_id' => $responseData['id'],
                        'type' => $type,
                        'activity' => $operation,
                        'message' => $message,
                    ];

                    // Check if 'parent_id' is set in $responseData
                    if (isset($responseData['parent_id'])) {
                        $logData['parent_type_id'] = $responseData['parent_id'];
                    }

                    if (isset($responseData['parent_type'])) {
                        $logData['parent_type'] = $responseData['parent_type'];
                    }

                    ActivityLog::create($logData);
                }
            }
        }

        return $response;
    }

    protected function convertOperation($operation)
    {
        return $this->operationMap[$operation] ?? $operation;
    }

    // Function to fetch title for a given type and ID
    private function getTitleForType($type, $id)
    {
        if ($type == 'media') {
            $record = Media::find($id);
        } elseif ($type == 'estimate' || $type == 'invoice') {
            $record = EstimatesInvoice::find($id);
        } else {
            $model = 'App\\Models\\' . str_replace('_', '', ucwords($type, '_')); // Assuming your model names are in singular form
            $record = $model::find($id);
        }

        if ($record) {
            return $type == 'user' || $type == 'client' ? ' ' . $record->first_name . ' ' . $record->last_name : ($type == 'payslip' ? ' ' . get_label('payslip_id_prefix', 'PSL-') . $id : ($type == 'payment' ? ' ' . get_label('payment_id', 'Payment ID') .' '. $id : ($type == 'estimate' ? ' ' . get_label('estimate_id_prefix', 'ESTMT-') . $id : ($type == 'invoice' ? ' ' . get_label('invoice_id_prefix', 'INVC-') . $id : (isset($record->title) ? ' ' . $record->title : ($type == 'media' ? ' ' . $record->file_name : ''))))));
        } else {
            return ''; // You may choose a default value for non-existent records
        }
    }
}
