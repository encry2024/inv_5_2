<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Device;
use App\Category;
use App\DeviceStatus;
use App\Information;
use App\Owner;
use App\DeviceLog;
use App\Note;
use App\User;
use App\Http\Requests\CreateDeviceRequest;


class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
        $this->middleware('auth');
    }


    public function index()
    {
        //code...
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        $ctr = 0;
        if (count($category) > 0) {
            return view('devices.create', compact('category', 'ctr'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateDeviceRequest $create_request)
    {
        //store device
        $store_device = Device::store_device($create_request, Input::all());
        return $store_device;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($device)
    {
        //
        $note = Note::where('device_id', $device->id)->where('past', 0)->first();
        return view('devices.edit', compact('device', 'note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($device, Request $request)
    {
        $return_device = Device::find($device->id);
        $device = $return_device->name;
        $return_device->delete();

        $category_slug = $request->get('category_slug');

        return redirect(route('category.show', [$category_slug]))->with('success_msg', 'Device :: ' .$device.' was successfully deleted');
    }

    public function fetch($category_id)
    {
        $all_device = Device::fetchAllDevice($category_id);

        return $all_device;
    }

    public function fetchStatus($id)
    {
        $fetch_status = Device::fetch_Status($id);

        return $fetch_status;
    }

    public function associateDevice(Request $request, $id)
    {
        $owner_id = $request->get('owner_id');
        $log = Device::cLog($owner_id, $id);

        return $log;
    }

    public function assocHistory($id)
    {
        $return_assocHistory = Device::fetch_assocHistory($id);

        return $return_assocHistory;
    }

    public function allAssoc()
    {
        $all_assoc = Device::fetchAllAssoc();

        return $all_assoc;
    }


    public function disassociateDevice($id)
    {
        $log = Device::disassocLog($id);

        return $log;
    }

    public function changeStatus(Request $request, $id)
    {
        $return_change_status = Device::change_status($request, $id);

        return $return_change_status;
    }

    public function openExcel(Request  $request)
    {
        $import_excel = Device::importDevice($request);

        return $import_excel;
    }

    public function deviceIndex()
    {
        return view('import.device');
    }

    public function deviceInformation()
    {
        $device_info = Device::getInformation();

        return $device_info;
    }

    public function assocDev($category_slug)
    {
        $associted_devices = Device::assoc_device($category_slug);

        return $associted_devices;
    }

    public function showAssocDev($category_slug)
    {
        $category = Category::whereSlug($category_slug)->first();

        return view('devices.associated_devices', compact('category'));
    }

    public function availDev($category_slug)
    {
        $available_devices = Device::avail_device($category_slug);

        return $available_devices;
    }

    public function showAvailDev($category_slug, Request $request)
    {
        $showAvailableDevices = Device::retrieveAvailableDevices($category_slug, $request);

        return $showAvailableDevices;
    }

    public function showDefectDev($category_slug)
    {
        $category = Category::whereSlug($category_slug)->first();

        return view('devices.defective_devices', compact('category'));
    }

    public function defectDev($category_slug)
    {
        $defect_devices = Device::defect_device($category_slug);

        return $defect_devices;
    }

    public function view_uncategorizedDevices()
    {
        return view('devices.uncategorized_devices');
    }

    public function viewAllAvailableDevices()
    {
        return view('devices.show_all');
    }

    public function showAllAvailableDevices()
    {
        $available_devices = Device::show_AllAvailableDevices();

        return $available_devices;
    }

    public function massDelete(Request $request)
    {
        $deleteDevices = Device::deleteAll($request->get('selectedDevices'));

        return $deleteDevices;
    }

    public function showDeviceInformation($device_slug)
    {
        $device = Device::whereSlug($device_slug)->with(['information', 'category'])->first();

        return view('devices.device_tab.information', compact('device'));
    }

    public function showDeviceStatus($device_slug)
    {
        $device = Device::show_device_status($device_slug);

        return $device;
    }
    
    public function showDeviceNote( $device_slug, Request $request ) {
        $device = Device::show_device_note($device_slug, $request);

        return $device;
    }

    public function showDeviceOwnership( $device_slug) {
        $device = Device::show_device_ownership($device_slug);

        return $device;
    }

    public function editDeviceInformation($device_slug)
    {
        $device = Device::editInformation($device_slug);

        return $device;
    }

    public function updateInformation($device_slug, Request $request, User $user, Information $information)
    {
        $device = Device::update_information($device_slug, $request, $user, $information);

        return $device;
    }
}
