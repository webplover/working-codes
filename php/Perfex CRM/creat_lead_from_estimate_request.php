<?php

/*
Module Name: Creat Lead From Estimate Request
Version: 1.0
Author: WebPlover
Author URI: https://webplover.com/
Description: This module automatically creates a new lead in Perfex CRM when a user submits an estimate request form. It extracts key information from the submission and checks for existing leads to avoid duplicates.
*/

function wpr_get_estimate($data)
{
  $id = $data['estimate_request_id'];

  $ci = &get_instance(); // Get CodeIgniter instance

  $ci->load->database(); // Load database library if not already loaded
  $ci->db->select('*');
  $ci->db->from('estimate_requests');
  $ci->db->where('id', $id);
  $query = $ci->db->get();
  return $query->row();
}


hooks()->add_action('estimate_requests_created', function ($data) {


  $submission_data = json_decode(wpr_get_estimate($data)->submission, true);

  $fields = ['Name', 'Email', 'Phone', 'City', 'Average Monthly Electricity Bill', 'Where did you find us'];
  $vars = ['name', 'email', 'phone', 'city', 'average_monthly_electricity_bill', 'where_did_you_find_us'];

  foreach ($fields as $index => $field) {
    ${$vars[$index]} = '';
    foreach ($submission_data as $item) {
      if (stripos($item['label'], $field) !== false) {
        ${$vars[$index]} = $item['value'];
        break;
      }
    }
  }

  // Check if lead with same email exists
  $CI = &get_instance();
  $CI->load->database();
  $CI->db->where('email', $email);
  $existing_lead = $CI->db->get('leads')->row();

  // If no lead with the same email exists, create a new lead
  if (!$existing_lead) {
    $CI->load->model('leads_model');



    $lead_data = [
      'name'    => $name,
      'email'   => $email,
      'phonenumber' => $phone,
      'city' => $city,
      'custom_fields' => [
        'leads' => [
          1 => $average_monthly_electricity_bill,
          2 => $where_did_you_find_us,
        ]
      ],
      'address' => '',
      'description' => '',
      'source'   => 1,
      'status'   => 1,
      'assigned' => 1,
    ];

    $CI->leads_model->add($lead_data);
  }
});
