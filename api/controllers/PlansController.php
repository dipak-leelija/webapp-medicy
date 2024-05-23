<?php
// PlansController.php

namespace Controllers;

use Models\PlanModel;

class PlansController
{
    private $planModel;

    public function __construct()
    {
        $this->planModel = new PlanModel();
    }

    public function getAllPlans()
    {
        return $this->planModel->getAllPlans();
    }
}
