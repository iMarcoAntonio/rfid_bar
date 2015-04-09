<?php

    class EmployeesController extends BaseController {

        function index() {
            $employees = Employee::all();
            $employees_position = EmployeePosition::all();
            return View::make('employees.index', array('employees' => $employees, 'employees_position' => $employees_position));
        }

        public function employeesDatatables() {
            $employees = Employee::join(DB::raw("(SELECT id employee_position_id, position FROM employee_position) employee_position"), "employees.employee_position_id", "=", "employee_position.employee_position_id") -> select(array('id', 'employee_name', 'position', 'epc'));
            return  Datatables::of($employees) -> make();
        }

        public function store($id = 0) {
            $input = Input::All();
            if ($id == 0) {
                $employee = new Employee();
            }
            else {
                $employee = Employee::find($id);
                if (!$employee) {
                    return App::abort(403, 'Item not found');
                }
            }
            $employee -> employee_name = $input['employee_name'];
            $employee -> epc = $input['epc'];
            $employee -> employee_position_id = $input['employee_position'];
            $employee -> save();
            
            return Response::json($employee);        
        }

        public function getEmployee($id) {

            $u = Employee::find($id);
            if ($u !== null) {
                return Response::json($u);
            }
            return App::abort(403, 'Item not found');
        }

        public function delete($id) {
            $u = User::find($id);
            if ($u) {
                $u -> delete();
            }
            return Response::json(array('ok' => 'ok'));
        }

        public function usersCSV() {
            $columns=array('id', 'username', 'email', 'created_at', 'updated_at');
            $headers=array('id', 'username', 'email', 'created_at', 'updated_at');
            CSVGenerate::sendCSV($columns, $headers, "users");
        }
    }
