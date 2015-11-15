<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;
use App\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class DeviceLog extends Eloquent
{
    //
    use RecordsActivity;
    protected $table = 'device_logs';

    public function owner()
    {
        return $this->belongsTo('App\Owner');
    }

    public function device()
    {
        return $this->belongsTo('App\Device')->withTrashed();
    }

    public function deviceOwner()
    {
        return $this->hasManyThrough('App\Device', 'App\Owner', 'id', 'owner_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function viewAssociation($request)
    {
        $devices = DB::table('devices')
            ->select('owners.*',
                DB::raw('inv_owners.firstName as "owner_fName"'),
                DB::raw('inv_owners.lastName as "owner_lName"'),
                DB::raw('inv_owners.slug as "owner_slug"'),
                'devices.*', DB::raw('inv_devices.name as "device_name"'), DB::raw('inv_devices.slug as "device_slug"'),
                'categories.*', DB::raw('inv_categories.name as "category_name"'), DB::raw('inv_categories.slug as "category_slug"'), 'users.*',
                DB::raw('inv_users.name as "user_name"'),
                DB::raw('inv_users.id as "user_id"'))
            ->join('owners', function ($join) use ($request) {
                $join->on('devices.owner_id', '=', 'owners.id');
            })
            ->leftJoin('categories', function ($join) {
                $join->on('devices.category_id', '=', 'categories.id');
            })
            ->leftJoin('users', function ($join) {
                $join->on('devices.user_id', '=', 'users.id');
            });

        if ($request->has('filter')) {
            $devices = $devices->where('owners.firstName', 'LIKE', '%'.$request->get('filter').'%')
                ->orWhere('owners.lastName', 'LIKE', '%'.$request->get('filter').'%')
                ->orWhere('devices.name', 'LIKE', '%'.$request->get('filter').'%');
        }

        $devices = $devices->latest('devices.created_at')->paginate(25);

        $devices->setPath('');
        return view('device_log.index', compact('devices'));
    }

    public static function showLogs($request)
    {
        $categories = Category::all();
        if (Request::has('filter')) {
            $date_filter = explode(' - ', $request->get('filter'));


            $deviceLogs = DeviceLog::with(['owner', 'device', 'user'])
                ->whereBetween('created_at', [$date_filter[0], $date_filter[1]])
                ->paginate(25);

            $deviceLogs->setPath('report');
        }
        return view('device_log.report', compact('deviceLogs', 'categories'));
    }

    # STATISTICS

    public static function getCountDeviceLog()
    {
        $devices = DB::table('devices')
            ->select('owners.*',
                DB::raw('inv_owners.firstName as "owner_fName"'),
                DB::raw('inv_owners.lastName as "owner_lName"'),
                DB::raw('inv_owners.slug as "owner_slug"'),
                'devices.*', DB::raw('inv_devices.name as "device_name"'), DB::raw('inv_devices.slug as "device_slug"'),
                'categories.*', DB::raw('inv_categories.name as "category_name"'), DB::raw('inv_categories.slug as "category_slug"'), 'users.*',
                DB::raw('inv_users.name as "user_name"'),
                DB::raw('inv_users.id as "user_id"'))
            ->join('owners', function ($join)  {
                $join->on('devices.owner_id', '=', 'owners.id');
            })
            ->leftJoin('categories', function ($join) {
                $join->on('devices.category_id', '=', 'categories.id');
            })
            ->leftJoin('users', function ($join) {
                $join->on('devices.user_id', '=', 'users.id');
            });

        $devices = $devices->latest('devices.created_at')->get();

        return count($devices);
    }
}
