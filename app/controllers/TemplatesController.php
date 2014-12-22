<?php

class TemplatesController extends BaseController {

    public $mailchimp;

    function __construct(){
        $this->mailchimp= new \KodeInfo\Templates\Mailchimp\TemplatesList();

        $this->beforeFilter('has_permission:mailchimp.pair_email', array('only' => array('all')));
        $this->beforeFilter('has_permission:mailchimp.view', array('only' => array('createPair','storePair')));
        $this->beforeFilter('has_permission:mailchimp.delete', array('only' => array('deletePair')));
    }

    public function all(){
        $templates = $this->mailchimp->getTemplatesList();
        $this->data['templates'] = $templates['user'];
        return View::make('mailchimp_templates.all',$this->data);
    }

    public function getPairAll(){
        $paired_templates = PairedTemplates::all();
        $this->data['paired_templates'] = $paired_templates;
        return View::make('mailchimp_templates.pair_all',$this->data);
    }

    public function view($template_id){
        $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";
        $template = $this->mailchimp->getTemplate($template_id);
        \File::put($file_path,$template['preview']);
        return View::make('emails.users.mailchimp_view',['email'=>'shellprog@gmail.com']);
    }

    public function createPair(){
        $email_views_config = Config::get('email_views');

        $paired_views_arr = PairedTemplates::lists('view');
        $email_views = [];

        if(sizeof($paired_views_arr)>0) {
            foreach (array_keys($email_views_config) as $view) {
                if (!in_array($view, $paired_views_arr)) {
                    $email_views[$view] = $email_views_config[$view];
                }
            }
        }else{
            $email_views = $email_views_config;
        }

        $this->data['email_views'] = $email_views;
        $this->data['templates'] = $this->mailchimp->getTemplatesList()['user'];

        return View::make('mailchimp_templates.pair_create',$this->data);
    }

    public function previewPair($pair_id){
        $paired = PairedTemplates::find($pair_id);

        $data = [];

        $template = $this->mailchimp->getTemplate($paired->template_id);

        $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";
        \File::put($file_path,$template['preview']);

        $send_mail = Input::get('send_mail')=="true"?true:false;

        if($paired->view=="emails.users.activate"){
            $user_mailer = new \KodeInfo\Mailers\UsersMailer();
            $data = $user_mailer->activate(Auth::user()->email,Auth::user()->name,[],true,$send_mail);
        }

        if($paired->view=="emails.users.password_changed"){
            $user_mailer = new \KodeInfo\Mailers\UsersMailer();
            $data = $user_mailer->password_changed(Auth::user()->email,Auth::user()->name,[],true,$send_mail);
        }

        if($paired->view=="emails.users.reset_password"){
            $user_mailer = new \KodeInfo\Mailers\UsersMailer();
            $data = $user_mailer->reset_password(Auth::user()->email,Auth::user()->name,[],true,$send_mail);
        }

        if($paired->view=="emails.users.welcome"){
            $user_mailer = new \KodeInfo\Mailers\UsersMailer();
            $data = $user_mailer->welcome(Auth::user()->email,Auth::user()->name,[],true,$send_mail);
        }

        if($send_mail){
            Session::flash('success_msg','Mail sent successfully');
            return Redirect::to('/templates/pair/all');
        }else{
            return View::make('emails.users.mailchimp_view',$data);
        }

    }

    public function storePair(){

        $email_views_config = Config::get('email_views');

        if(Input::has('view')&&Input::has('template_id')){

            $paired = new PairedTemplates();
            $paired->name = $email_views_config[Input::get('view')];
            $paired->view = Input::get('view');
            $paired->template_id = Input::get('template_id');
            $paired->save();

            Session::flash('success_msg','Template paired successfully');

            return Redirect::to('/templates/pair/all');
        }else{
            Session::flash('error_msg','All field are required');
            return Redirect::to('/templates/pair/create');
        }
    }

    public function deletePair($pairing_id){
        PairedTemplates::where('id',$pairing_id)->delete();
        Session::flash('success_msg','Pairing deleted successfully');
        return Redirect::to('/templates/pair/all');
    }
}