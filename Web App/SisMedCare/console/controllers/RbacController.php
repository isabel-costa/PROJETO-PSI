<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // --- ROLES ---
        $admin = $auth->createRole('admin');
        $doctor = $auth->createRole('doctor');
        $secretary = $auth->createRole('secretary');

        $auth->add($admin);
        $auth->add($doctor);
        $auth->add($secretary);

        // --- PERMISSIONS (Admin) ---
        $createDoctor = $auth->createPermission('createDoctor');
        $createDoctor->description = 'Create doctor accounts';
        $auth->add($createDoctor);

        $updateDoctor = $auth->createPermission('updateDoctor');
        $updateDoctor->description = 'Edit doctor accounts';
        $auth->add($updateDoctor);

        $deleteDoctor = $auth->createPermission('deleteDoctor');
        $deleteDoctor->description = 'Delete doctor accounts';
        $auth->add($deleteDoctor);

        $viewDoctors = $auth->createPermission('viewDoctors');
        $viewDoctors->description = 'View doctor list';
        $auth->add($viewDoctors);

        $createSecretary = $auth->createPermission('createSecretary');
        $createSecretary->description = 'Create secretary accounts';
        $auth->add($createSecretary);

        $updateSecretary = $auth->createPermission('updateSecretary');
        $updateSecretary->description = 'Edit secretary accounts';
        $auth->add($updateSecretary);

        $deleteSecretary = $auth->createPermission('deleteSecretary');
        $deleteSecretary->description = 'Delete secretary accounts';
        $auth->add($deleteSecretary);

        $viewSecretaries = $auth->createPermission('viewSecretaries');
        $viewSecretaries->description = 'View secretary list';
        $auth->add($viewSecretaries);

        $createMedicine = $auth->createPermission('createMedicine');
        $createMedicine->description = 'Create a medicine';
        $auth->add($createMedicine);

        $updateMedicine = $auth->createPermission('updateMedicine');
        $updateMedicine->description = 'Edit a medicine';
        $auth->add($updateMedicine);

        $deleteMedicine = $auth->createPermission('deleteMedicine');
        $deleteMedicine->description = 'Delete a medicine';
        $auth->add($deleteMedicine);

        $viewMedicines = $auth->createPermission('viewMedicines');
        $viewMedicines->description = 'View medicine list';
        $auth->add($viewMedicines);

        // --- PERMISSIONS (Doctor) ---
        $viewSchedule = $auth->createPermission('viewSchedule');
        $viewSchedule->description = 'View personal Appointment schedule';
        $auth->add($viewSchedule);

        $viewPatientHistory = $auth->createPermission('viewPatientHistory');
        $viewPatientHistory->description = 'View patient medical history';
        $auth->add($viewPatientHistory);

        $addClinicalInfo = $auth->createPermission('addClinicalInfo');
        $addClinicalInfo->description = 'Add clinical information';
        $auth->add($addClinicalInfo);

        $editClinicalInfo = $auth->createPermission('editClinicalInfo');
        $editClinicalInfo->description = 'Edit patient clinical information';
        $auth->add($editClinicalInfo);

        $prescribeMedication = $auth->createPermission('prescribeMedication');
        $prescribeMedication->description = 'Prescribe medication to patients';
        $auth->add($prescribeMedication);

        // --- PERMISSIONS (Secretary) ---
        $viewAppointmentRequests = $auth->createPermission('viewAppointmentRequests');
        $viewAppointmentRequests->description = 'View appointment requests';
        $auth->add($viewAppointmentRequests);

        $approveAppointment = $auth->createPermission('approveAppointment');
        $approveAppointment->description = 'Approve appointment requests';
        $auth->add($approveAppointment);

        $rejectAppointment = $auth->createPermission('rejectAppointment');
        $rejectAppointment->description = 'Reject appointment requests';
        $auth->add($rejectAppointment);

        $viewDoctorAvailability = $auth->createPermission('viewDoctorAvailability');
        $viewDoctorAvailability->description = 'View doctor availability';
        $auth->add($viewDoctorAvailability);

        $addPatient = $auth->createPermission('addPatient');
        $addPatient->description = 'Add patient personal data';
        $auth->add($addPatient);

        $editPatient = $auth->createPermission('editPatient');
        $editPatient->description = 'Edit patient personal data';
        $auth->add($editPatient);

        $searchPatient = $auth->createPermission('searchPatient');
        $searchPatient->description = 'Search patients by name or healthcare number';
        $auth->add($searchPatient);

        // --- ASSIGN PERMISSIONS TO ROLES ---
        // Doctor permissions
        $auth->addChild($doctor, $viewSchedule);
        $auth->addChild($doctor, $viewPatientHistory);
        $auth->addChild($doctor, $addClinicalInfo);
        $auth->addChild($doctor, $editClinicalInfo);
        $auth->addChild($doctor, $prescribeMedication);
        $auth->addChild($doctor, $searchPatient);

        // Secretary permissions
        $auth->addChild($secretary, $viewAppointmentRequests);
        $auth->addChild($secretary, $approveAppointment);
        $auth->addChild($secretary, $rejectAppointment);
        $auth->addChild($secretary, $viewDoctorAvailability);
        $auth->addChild($secretary, $addPatient);
        $auth->addChild($secretary, $editPatient);
        $auth->addChild($secretary, $searchPatient);

        // Admin permissions
        $auth->addChild($admin, $createDoctor);
        $auth->addChild($admin, $updateDoctor);
        $auth->addChild($admin, $deleteDoctor);
        $auth->addChild($admin, $viewDoctors);
        $auth->addChild($admin, $createSecretary);
        $auth->addChild($admin, $updateSecretary);
        $auth->addChild($admin, $deleteSecretary);
        $auth->addChild($admin, $viewSecretaries);
        $auth->addChild($admin, $createMedicine);
        $auth->addChild($admin, $updateMedicine);
        $auth->addChild($admin, $deleteMedicine);
        $auth->addChild($admin, $viewMedicines);
    }
}
