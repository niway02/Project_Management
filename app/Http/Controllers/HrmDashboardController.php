<?php  

namespace App\Http\Controllers;  

use Illuminate\Http\Request;
use App\Models\LaborType;
use App\Models\Labor;
use App\Models\ResourceRequest;
class HrmDashboardController extends Controller  
{  
    public function index()  
    {  
        $laborPossitions = LaborType::all();
        $labor = Labor::all();
        $allocatedLabor = Labor::where('status','allocate')->get();
        $unallocatedLabor = Labor::where('status','unallocated')->get();

        $pendingResourceRequest = ResourceRequest::where('status','Pending')->where('type','labor')->get();
        $approvedResourceRequest = ResourceRequest::where('status','approved')->where('type','labor')->get();
   
        $totalPendingRequest  = count($pendingResourceRequest);
        $totalApprovedRequest  = count($approvedResourceRequest);
        $total_labor = count($labor);


        // Pass the data to the view  
        return view('hrm.dashboard', [  
            'total_labor' => $total_labor,
            'newRequest' => $total_labor,
            'totalPendingRequest' => $totalPendingRequest,
            'totalApprovedRequest' =>$totalApprovedRequest,
            'laborPossitions'=>$laborPossitions,
            'allocatedLabor' =>  count($allocatedLabor),
            'unallocatedLabor' =>count($unallocatedLabor)
        ]);  


    }  
    public function list()
    {
        $search = request('search');
        $sort = request('sort') ?? 'id';
        $order = request('order') ?? 'DESC';
    
        // Eager load LaborType and User relationships
        $priority = Labor::with(['LaborType', 'User','laborSites'])->orderBy($sort, $order);
    
        if ($search) {
            $priority = $priority->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
    
        $total = $priority->count();
    
        $priority = $priority->paginate(request('limit'))->through(
            fn ($priority) => [
                'id' => $priority->id,
                'name' => $priority->User->first_name . ' ' . $priority->User->last_name, // Update to fetch from User
                'position' => $priority->LaborType->labor_type_name ?? null,
                'hourly_rate' => $priority->LaborType->hourly_rate . " " . "Birr" ?? null,
                'status' => $priority->status,
                'address' => $priority->User->address,
                'photo' => "<div class='avatar avatar-md pull-up' title='" . $priority->user->first_name . " " . $priority->user->last_name . "'>
                    <a href='/users/profile/" . $priority->user->id . "'>
                    <img src='" . ($priority->user->photo ? asset('storage/' . $priority->user->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                    </a>
                    </div>",
              'current_site' => $priority->laborSites->isNotEmpty() ? $priority->laborSites->first()->name : null, // Fetch current site name
                'availability' => $priority->LaborType->availability ?? null,
                'created_at' => format_date($priority->created_at, true),
                'updated_at' => format_date($priority->updated_at, true),
                
            ]
        );
    
        return response()->json([
            'rows' => $priority->items(),
            'total' => $total,
        ]);
    }
}