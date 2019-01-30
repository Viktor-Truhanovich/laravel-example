<?php

namespace App\Http\ViewComposers;

use App\Repositories\BusinessCenterRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\ServiceAddRepository;
use App\Repositories\ServiceTypeRepository;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class CountRecordingComposer
{
    protected $users;
    protected $user;
    protected $businessCenter;
    protected $companyRepository;
    protected $serviceRepository;
    protected $serviceAddRepository;
    protected $serviceTypeRepository;
    protected $serviceTypeAddRepository;

    public function __construct(UserRepository $userRepository, BusinessCenterRepository $businessCenterRepository,
                                CompanyRepository $companyRepository, ServiceRepository $serviceRepository,
                                ServiceTypeRepository $serviceTypeRepository, ServiceAddRepository $serviceAddRepository)
    {
        $this->users = $userRepository;
        $this->businessCenter = $businessCenterRepository;
        $this->companyRepository = $companyRepository;
        $this->serviceRepository = $serviceRepository;
        $this->serviceAddRepository = $serviceAddRepository;
        $this->serviceTypeRepository = $serviceTypeRepository;
    }

    public function compose(View $view)
    {
        $this->user = \Auth::user();
        if(empty($this->user)) {
            return $view;
        }
        $view->with('userCount', $this->getCountUser());
        $view->with('businessCenterCount', $this->getCountBusinessCenter());
        $view->with('companyCount', $this->getCountCompany());
        $view->with('employeeCount', $this->getCountEmployee());
        $view->with('serviceCount', $this->getCountService());
        $view->with('serviceAddCount', $this->getCountServiceAdd());
        $view->with('serviceTypeCount', $this->getCountServiceType());
        $view->with('isBusinessCenterSettings', $this->isBusinessCenterSettings());
    }

    public function getCountUser()
    {
        if ($this->user->can('all_show_user_all_business_center')) {
           return $this->users->peopleFromPermissionCountAllBusinessCenter(['system_user']);
        } else {
           return $this->users->peopleFromPermissionCurrentBusinessCenter($this->user, ['system_user']);
        }
    }

    public function getCountBusinessCenter() {
        if ($this->user->can('all_show_business_center')) {
            return $this->businessCenter->getCount();
        } else {
            return $this->businessCenter->businessCenterCountCurrent($this->user);
        }
    }
    public function getCountCompany() {
        if ($this->user->can('all_show_company')) {
            return $this->companyRepository->getCount();
        } else {
            return $this->companyRepository->companyCountCurrent($this->user);
        }
    }

    public function getCountEmployee()
    {
        if ($this->user->can('all_show_employee')) {
            return $this->users->peopleFromPermissionCountAllBusinessCenter(['system_employee']);
        } else {
            return $this->users->peopleFromPermissionCurrentBusinessCenter($this->user, ['system_employee']);
        }
    }

    public function getCountService()
    {
        if ($this->user->can('all_show_service')) {
            return $this->serviceRepository->getCount();
        } else {
            return $this->serviceRepository->serviceCountCurrentBusinessCenter($this->user);
        }
    }

    public function getCountServiceAdd()
    {
        if ($this->user->can('all_show_service_add')) {
            return $this->serviceAddRepository->getCount();
        } else {
            return $this->serviceAddRepository->serviceAddCountCurrentBusinessCenter($this->user);
        }
    }

    public function getCountServiceType()
    {
        return $this->serviceTypeRepository->getCount();
    }

    public function isBusinessCenterSettings()
    {
        if ($this->user->can('booking-general-statistic')
            && !empty($this->user->businessCenter->serviceTypes)
            && !empty($this->user->businessCenter->serviceTypes()->isReservation()->currencyPoint()->first())) {
            return true;
        } else {
            return false;
        }
    }
}
