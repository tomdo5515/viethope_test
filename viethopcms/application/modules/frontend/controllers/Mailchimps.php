<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailchimps extends FrontendController
{
    public function __construct() {
        parent::__construct();
        $this->load->library('MailChimp');
    }
 
    function subscribe($email) {
        $this->output->set_content_type('application/json');
        $response = ['status' => 100];

        $list_id = $this->config->item('mailchimp_list_id');
        $result = $this->mailchimp->post("lists/$list_id/members", [
            'email_address' => $email,
            'status'        => 'subscribed',
        ]);

        $response['status'] = $result['status'];
        $this->output->set_output(json_encode($response));
    }

    function unsubscribe($email) {
        $response = ['status' => 100];
        $list_id = $this->config->item('mailchimp_list_id');
        $subscriber_hash = $MailChimp->subscriberHash($email);
        $result = $this->mailchimp->delete("lists/$list_id/members/$subscriber_hash");

        $response['status'] = $result['status'];
    }
}