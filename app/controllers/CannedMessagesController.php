<?php

class CannedMessagesController extends BaseController
{

    public function  __construct()
    {
        $this->beforeFilter('has_permission:canned_messages.create', array('only' => array('create', 'store')));
        $this->beforeFilter('has_permission:canned_messages.edit', array('only' => array('edit', 'update')));
        $this->beforeFilter('has_permission:canned_messages.view', array('only' => array('all')));
        $this->beforeFilter('has_permission:canned_messages.delete', array('only' => array('delete')));
    }

    public function create()
    {
        $companies = Company::where("user_id", Auth::user()->id)->get();

        if (\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();

            $this->data['department'] = Department::where('id', $department_admin->department_id)->first();

            $this->data['department'] = Department::where('id', $department_admin->department_id)->first();
            $this->data["company"] = Company::where('id', $this->data['department']->company_id)->first();
            $this->data['operators'] = API::getDepartmentOperators($department_admin->department_id);

        } elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

        } else {

            $this->data['departments'] = [];
            $this->data['operators'] = [];

            if (sizeof($companies) > 0) {
                $company_departments = API::getCompanyDepartments($companies[0]->id);

                if (sizeof($company_departments) > 0) {
                    $this->data['departments'] = $company_departments;
                    $this->data['operators'] = API::getDepartmentOperators($company_departments[0]->id);
                }
            }

            $this->data["companies"] = $companies;

        }


        return View::make('canned_messages.create', $this->data);
    }

    public function store()
    {
        $v = Validator::make(["message" => Input::get("message"), "company" => Input::get("company"),
            "department" => Input::get("department"), "operator" => Input::get("operator")],
            ["message" => "required", "company" => "required|exists:companies,id",
                "department" => "required|exists:departments,id", "operator" => "required|exists:users,id"]);

        if ($v->passes()) {
            $message = new CannedMessages();
            $message->message = Input::get('message');
            $message->company_id = Input::get('company');
            $message->department_id = Input::get('department');
            $message->operator_id = Input::get('operator');
            $message->save();

            Session::flash('success_msg', 'Canned message successfully created');
            return Redirect::to('/canned_messages/all');
        } else {
            Session::flash('error_msg', Utils::buildMessages($v->messages()->all()));
            return Redirect::to('/canned_messages/create')->withInput();
        }

    }

    public function delete($message_id)
    {
        try {
            CannedMessages::findOrFail($message_id)->delete();
            Session::flash('success_msg', "Canned message deleted successfully");
            return Redirect::to('/canned_messages/all');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash('error_msg', "Canned message not found");
            return Redirect::to('/canned_messages/all');
        }
    }

    public function all()
    {
        $messages = CannedMessages::all();

        foreach ($messages as $message) {

            $operator = User::find($message->operator_id);
            $department = Department::find($message->department_id);
            $company = Company::find($message->company_id);

            $message->operator = $operator;
            $message->department = $department;
            $message->company = $company;
        }

        $this->data['messages'] = $messages;

        return View::make('canned_messages.all', $this->data);
    }

}