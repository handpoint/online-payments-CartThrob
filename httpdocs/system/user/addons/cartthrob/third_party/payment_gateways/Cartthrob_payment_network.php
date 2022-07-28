<?php
/**
 * Payment Network payment module for CartThrob 3
 *
 * This software is distributed under GNU GPL.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @package default
 * @author Payment Network <https://www.example.com>
 * @version 1.0
 */
class Cartthrob_payment_network_hosted extends Cartthrob_payment_gateway
{
  public $title = 'payment_network_title';
  public $overview = 'payment_network_description';
  public $settings = array(
    array(
      'name' => 'payment_network_merchant_id',
      'short_name' => 'merchant_id',
      'type' => 'text',
      'default' => '155928',
    ),
    array(
      'name' => 'payment_network_signature_key',
      'short_name' => 'signature_key',
      'type' => 'text',
      'default' => 'm3rch4nts1gn4tur3k3y'
    ),
    array(
      'name' => 'payment_network_form_responsive',
      'short_name' => 'form_responsive',
      'type' => 'select',
      'options' => array(
        'Y' => 'Yes',
        'N' => 'No'
      ),
    ),
    array(
      'name' => 'payment_network_custom_url',
      'short_name' => 'custom_url',
      'type' => 'text',
      'default' => 'https://commerce-api.handpoint.com/hosted/'
    ),
    array(
      'name' => 'payment_network_currency_code',
      'short_name' => 'currency_code',
      'type' => 'text',
      'default' => '826'
    ),
    array(
      'name' => 'payment_network_country_code',
      'short_name' => 'country_code',
      'type' => 'text',
      'default' => '826'
    ),
  );

  public $required_fields = array(
		'first_name',
		'last_name',
		'address',
		'city',
		'state',
		'zip',
		'country',
  );

  public $fields = array(
    'first_name',
    'last_name',
    'address',
    'address2',
    'city',
    'state',
    'zip',
    'country',
    'phone',
    'email_address',
  );

  /**
   * _process_payment function
	 *
 	 * @param string $credit_card_number
     * @author Payment Network <https://www.example.com>
 	 * @access public
	 * @return array $ret
	 **/
   public function charge($credit_card_number){

     $total = round($this->total()*100);

     $post_array = array(
       'merchantID' => $this->plugin_settings('merchant_id'),
       'type' => 1,
       'action' => 'SALE',
       'amount' => $total,
       'countryCode' => $this->plugin_settings('country_code'),
       'currencyCode' => $this->plugin_settings('currency_code'),
       'customerName' => $this->order('first_name') . ' ' . $this->order('last_name'),
       'customerAddress' => $this->order('address') . ' ' .
       $this->order('address2') . ' ' .
       $this->order('city') . ' ' .
       $this->order('state'),
       'customerPostcode' => $this->order('zip'),
       'customerPhone' => $this->order('phone'),
       'customerEmail' => $this->order('email_address'),
       'transactionUnique' => $this->order('entry_id'),
       'orderRef' => $this->order('entry_id'),
       'redirectURL' => $this->response_script(ucfirst(get_class($this)), array("payment_networkResponse")),
       'formResponsive' => $this->plugin_settings('form_responsive'),
       'merchantData' => 'CartThrob-3',
       'threeDSVersion' => 2,
     );

     // Create the signature using the function called below.
   	 $post_array['signature'] = $this->createSignature($post_array, $this->plugin_settings('signature_key'));

     $this->gateway_exit_offsite($post_array, $url = FALSE, $jump_url = $this->plugin_settings('custom_url'));
     //exit;

   }

   /**
    *
    * Payment Network response
    *
    * @param array $post
    * @author Payment Network
    * @return array $auth
    */
   public function extload($post){

     $order_id = $post['transactionUnique'];
	if ($order_id) {
		$this->relaunch_cart(NULL, $order_id);
	}

     $auth = array(
       'authorized' => FALSE,
       'error_message' => NULL,
       'failed' => TRUE,
       'declined' => FALSE,
       'transaction_id' => NULL,
	   'processing' => FALSE,
     );


     if ((isset($post['responseCode']) && $post['responseCode'] == '0')) {
       $auth = array(
         'authorized' => TRUE,
         'error_message' => $post['responseMessage'],
         'failed' => FALSE,
         'declined' => FALSE,
         'transaction_id' => $post['xref'],
       );
     } else {
	$message = $post['responseMessage'];
       $auth = array(
         'authorized' => FALSE,
         'error_message' => $message,
         'failed' => TRUE,
         'declined' => FALSE,
         'transaction_id' => NULL,
	     'processing' => FALSE,
       );

     }

     if ( ! $this->order('return')) {
     	$this->update_order(array('order_id' => $post['transactionUnique'], 'error_message' => $post['error_message']));
     }

     $this->checkout_complete_offsite($auth,  $this->order('entry_id'), 'return');
     exit;
   }

   /**
    *
    * Payment Network signature creation
    *
    * @param array $data | string $key
    * @author Payment Network
    * @return array $ret
    */
   function createSignature(array $data, $key){
     // Sort by field name
     ksort($data);

     // Create the URL encoded signature string
     $ret = http_build_query($data, '', '&');

     // Normalise all line endings (CRNL|NLCR|NL|CR) to just NL (%0A)
     $ret = str_replace(array('%0D%0A', '%0A%0D', '%0D'), '%0A', $ret);

     // Hash the signature string and the key together
     $ret = hash("SHA512", $ret . $key);

     // Return the calculated signature
     return $ret;

   }
}
 ?>
