<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServiceRequest;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceType;
use App\Repositories\ServiceRepository;
use App\Services\BookingService;
use App\Services\ServiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends BaseController
{
    protected $serviceRepository;
    protected $serviceService;
    protected $bookingService;

    public function __construct(ServiceRepository $serviceRepository, ServiceService $service, BookingService $bookingService)
    {
        parent::__construct();
        $this->serviceRepository = $serviceRepository;
        $this->serviceService    = $service;
        $this->bookingService    = $bookingService;
        $this->middleware('permission:service-list');
        $this->middleware('permission:service-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:service-edit',   ['only' => ['edit', 'update']]);
        $this->middleware('permission:service-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('all_show_service')) {
            $services = $this->serviceRepository->getAll();
        } else {
            $services = $this->serviceRepository->serviceCurrentBusinessCenter($this->user);
        }

        return view('admin.service.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Service::class);
        $listServiceTypes   = $this->user->businessCenter->serviceTypes->pluck('name', 'id');
        $subscriptionsCount = Auth::user()->businessCenter->calculationSubscription()->count();

        return view('admin.service.create', compact('listBusinessCenters', 'listServiceTypes',
            'subscriptionsCount'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ServiceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        $this->authorize('create', Service::class);
        $service = Service::create($request->all());
        $this->serviceService->saveServiceUnit($request, $service->id);

        return redirect()->route('admin.service.index')->with('success', 'Услуга успешно добавлена');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $this->authorize('view', $service);

        return view('admin.service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $this->authorize('update', $service);
        $listServiceTypes = $this->serviceService->getServiceTypesForBusinessCenter();
        $subscriptionsCount = Auth::user()->businessCenter->calculationSubscription()->count();

        return view('admin.service.edit', compact( 'listServiceTypes', 'service', 'subscriptionsCount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ServiceRequest $request
     * @param  \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize('update', $service);
	$service->update($request->all());
	$this->serviceService->saveServiceUnit($request, $service->id);
	$service->with('invoices')->get();

        foreach ($invoices as $invoice) {
            $this->bookingService->recalculatePoints($invoice);
        }

        return redirect()->route('admin.service.index')->with('success', 'Услуга успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();

        return redirect()->route('admin.service.index')->with('success', 'Услуга успешно удалена');
    }
}
