<?php
/**
* Admin View: Page - Accounts
*/
if ( ! defined( 'ABSPATH' ) ) {
exit;
}

      global $wpoptins;
      global $wpoptins_accounts;

      $current_url = urlencode_deep(esc_url(admin_url('admin.php?page=wpop_accounts')));
?>	

<div class="xo_wrap z-depth-1">
  <span class="xo_loader_holder">
    <img class="loader_img fadeIn" src="<?php echo WPOP_URL;?>/assets/images/wpoptin-logo.png">
  </span>
  <div class="xo_header fadeIn">
    <div class="xo_sliders_wrap">
     <img src="<?php echo WPOP_URL;?>/assets/images/wpop-menu-icon.png" />
    </div> 
    <div class="xo-addnew-right">
      <?php if(!empty($wpoptins)){ ?>
      <a href="<?php echo esc_url(admin_url('admin.php?page=wpop_overview'))?>" id="xo_stats" class="xo_stats wpop-tooltipped" data-position="bottom" data-tooltip="<?php esc_html_e("Analytics", 'wpoptin');?>">
        <i class="material-icons">insert_chart
        </i>
      </a>
      <?php } 	?>
      <a href="javascript:void(0);" id="xo_account_add" class="ox-add-new wpop-tooltipped" data-position="bottom" data-tooltip="<?php esc_html_e("Add New", 'wpoptin');?>">
        <i class="material-icons">add
        </i>
      </a>
    </div>	 
  </div>
  <div class="xo-settings-genral-wrapper">
    <?php 
if(!empty($wpoptins_accounts)){ ?>
    <table class="xo_accounts_table xo_table_layout responsive-table highlight">
      <thead>
        <tr>
          <th>
            <?php esc_html_e("Name", 'wpoptin');?>
          </th>
          <th>
            <?php esc_html_e("Service provider", 'wpoptin');?>
          </th>
          <th>
            <?php esc_html_e("Subscribers", 'wpoptin');?>
          </th>
          <th>
            <?php esc_html_e("Actions", 'wpoptin');?>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php  $i = null;	
if($wpoptins_accounts)	
{foreach ($wpoptins_accounts as $wpoptins_account ) {
$i++;
?>
        <tr class="row_<?php echo $wpoptins_account['ID'] ?> tr_parent">
          <td>
            <?php echo $wpoptins_account['title'] ?>
          </td>
          <td>
            <?php echo $wpoptins_account['service_provider'] ?>
          </td>
          <td>
          </td>
          <td>
            <div class="action_holder">
              <a href="javascript:void(0);" id="xo_remove<?php echo $i ?>" data-is_parent="true" data-remove_id="<?php echo $wpoptins_account['ID'] ?>" class="wpop_remove_db wpop-tooltipped" data-position="right" data-tooltip="<?php esc_html_e("Remove", 'wpoptin');?>">
                <i class="material-icons">delete
                </i>
              </a>
            </div>
          </td>
        </tr>
        <?php	if($wpoptins_account['lists']):
        foreach ($wpoptins_account['lists'] as $single_list) { ?>
            <tr class="row_<?php echo $wpoptins_account['ID'] ?>">
              <td>
                <?php echo $single_list['name'] ?>
              </td>
              <td>
              </td>
              <td>
                <?php echo $single_list['subscribers'] ?>
              </td>
              <td>
              </td>
            </tr>
        <?php }
endif;
}} ?>
      </tbody>
    </table>
    <?php }  ?>
    <div class="no_xo_accounts">
      <div class="xo_optin_accounts">
        <ul>
          <li class="xo_account_provider_li">
            <label class="label">
              <?php esc_html_e("Select email provider", 'wpoptin');?>
            </label>
            <select class="xo_account_provider_page"><option value="0"><?php esc_html_e("--Select one--", 'wpoptin');?>
              </option>
              <?php $terms = get_terms( 'wpop_accounts_tax', [ 'hide_empty' => false] );
                if(isset($terms))
                {foreach ($terms as $term ) {  ?>
              <option value="<?php echo $term->slug ?>"><?php echo $term->name ?>
              </option>
              <?php }} ?>
            </select>
          </li>
          <div class="xo_lists_holder">
          </div>
          <div class="xo_account_add_holder xo_account_add_holder_mailchimp">
            <li class="xo_authBtn_li">
              <a href="https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=544239805685&redirect_uri=<?php echo urlencode('https://wpxoptin.com/mc-vZEhNb7e8PFA/index.php')?>&state=<?php echo $current_url ?>" class="btn waves-effect waves-light xo_acount_auth_page">
                <?php esc_html_e("Authenticate", 'wpoptin');?>
                <i class="material-icons right">vpn_key
                </i>
              </a>
            </li>	
          </div>
          <div class="xo_account_add_holder xo_account_add_holder_constant_contact">
           
              <?php 

              $cc_client_ids = ['hx3xc86m93xmvspzcyzgw6ad', 'pmrc28fx79vtruyz5axhhjm9', 'sjz26qf8nq5ny9xkd6dux5w8'];

              $cc_client_id = array_rand($cc_client_ids);

              $cc_client_id = $cc_client_ids[$cc_client_id];

              $cc_current_url = urlencode_deep(admin_url('admin.php?page=wpop_new&goal='.$goal.'&type='.$type.'&client_id='.$cc_client_id.''));

              ?>

                <a href="https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize?response_type=code&client_id=<?php echo $cc_client_id ?>&redirect_uri=http://wpxoptin.com/optins-integrations-vZEhNb7e8PFA/cc-6WvtkhasGyJ5FNWj/index.php&state=<?php echo $cc_current_url ?>" class="btn waves-effect waves-light xo_acount_auth_page">
                <?php esc_html_e("Authenticate", 'wpoptin');?>
                <i class="material-icons right">vpn_key
                </i>
              </a>

          </div>
          <div class="xo_account_add_holder xo_account_add_holder_campaign_monitor">
              
            <a href="https://api.createsend.com/oauth?type=web_server&client_id=120333&redirect_uri=http://wpxoptin.com/optins-integrations-vZEhNb7e8PFA/cm-wYChvuR2wvj5/index.php&scope=ManageLists&state=<?php echo $current_url ?>" class="btn waves-effect waves-light xo_acount_auth_page">
                <?php esc_html_e("Authenticate", 'wpoptin');?>
                <i class="material-icons right">vpn_key
                </i>
              </a>

          </div>
          <div class="xo_account_add_holder xo_account_add_holder_mad_mimi">
            <li>
              <div class="input-field col s12">
                <input id="mm_acount_name" type="text" name="mm_acount_name">
                <label for="mm_acount_name">
                  <?php esc_html_e("Account name", 'wpoptin');?>
                </label>
              </div>
            </li>
            <li>
              <div class="input-field col s12">
                <input id="mm_user_name" type="text" name="mm_user_name">
                <label for="mm_user_name">
                  <?php esc_html_e("User Name", 'wpoptin');?>
                </label>
              </div>
            </li>
            <li class="xo_apikey_li">
              <div class="input-field col s12">
                <input id="mm_acount_api" type="password" name="mm_acount_api">
                <label for="mm_acount_api">
                  <?php esc_html_e("Api key", 'wpoptin');?>
                </label>
              </div>
            </li>
            <li class="xo_authBtn_li">
              <button class="btn waves-effect waves-light xo_acount_auth_mad_mimi">
                <?php esc_html_e("Authenticate", 'wpoptin');?>
                <i class="material-icons right">vpn_key
                </i>
              </button>
            </li>	
          </div>
          <div class="xo_account_add_holder xo_account_add_holder_mailpoet">
            <li>
              <div class="input-field col s12">
                <input id="mp_acount_name" type="text" name="mp_acount_name">
                <label for="mp_acount_name">
                  <?php esc_html_e("Account name", 'wpoptin');?>
                </label>
              </div>
            </li>
            <li class="xo_authBtn_li">
              <button class="btn waves-effect waves-light xo_acount_auth_mailpoet">
                <?php esc_html_e("Authenticate", 'wpoptin');?>
                <i class="material-icons right">vpn_key
                </i>
              </button>
            </li>	
          </div>
        </ul>	
        <!-- Submit button html starts here. -->
        <span class="xo_sub_holder" style="display: none;">
          <a href="<?php esc_url(admin_url('admin.php?page=wpop_accounts')) ?>" class="btn waves-effect waves-light">
            <?php esc_html_e("View All", 'wpoptin');?>
          </a>	
        </span>
      </div>		
      <!-- Submit button html ends here. -->
      <div class="xo_optin_accounts_right">
        <div class="xo_lost_holder">
          <div class="card">
            <div class="wpop-card-icon">
              <i class="material-icons dp48">sentiment_very_dissatisfied
              </i>
            </div>	
            <h4>
              <?php esc_html_e("Feeling Lost?", 'wpoptin');?>
            </h4>
            <div class="card-content">
              <a target="_blank" href="https://wpxoptin.com/documentation/how-to-create-opt-in/" class="btn waves-effect waves-light"> 
                <?php esc_html_e("Check this out", 'wpoptin');?> 
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php  if( wpop_fs()->is_free_plan() ) { ?>

     <div id="wpop-upgrade-mail-poet" class="modal wpop-upgrade-modal">
          <div class="modal-content">
            <div class="wpop-modal-content"> 
              <span class="wpop-lock-icon"><i class="material-icons">lock_outline</i> </span>
                <h5><?php esc_html_e("Premium Feature", 'wpoptin');?></h5>
                <p><?php esc_html_e("We're sorry, Mailpoet integration is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Keep increase conversion by 10x while testing new ideas.", 'wpoptin');?></p>
                <hr>
                <p><?php esc_html_e("Use following coupon code to get 27% discount for limited time", 'wpoptin');?><br><code>BFCMOP</code></p>
                <a href="<?php echo wpop_fs()->get_upgrade_url() ?>" class="waves-effect waves-light btn z-depth-3"><i class="material-icons right">lock_open</i><?php esc_html_e("Upgrade to pro", 'wpoptin');?></a>
                                    
            </div>
          </div>   
      </div>
 <?php   } ?>
</div>

<?php if ( wpop_fs()->is_free_plan() ) {

    $banner_info = $this->wpop_upgrade_banner();

    if( $banner_info ){ ?>

    <div class="wpoptin-upgrade-wrapper z-depth-2">
        <h2><?php if( isset( $banner_info['name'] ) ){  esc_html_e( $banner_info['name'] ); } ?>
            <b><?php if( isset( $banner_info['bold'] ) ){ esc_html_e( $banner_info['bold'] ); }  ?></b></h2>
        <p><?php if( isset( $banner_info['description'] ) ){ esc_html_e( $banner_info['description'] ); }  ?></p>
        <p><?php if( isset( $banner_info['discount-text'] ) ){ esc_html_e( $banner_info['discount-text'] ); }  ?>
            <code><?php if( isset( $banner_info['coupon'] ) ){ esc_html_e( $banner_info['coupon'] ); }  ?></code>
        </p>
        <a href="<?php echo esc_url( wpop_fs()->get_upgrade_url() ) ?>"
           class="waves-effect waves-light btn"><i class="material-icons right">lock_open</i><?php if( isset( $banner_info['button-text'] ) ){  esc_html_e( $banner_info['button-text'] ); } ?>
        </a>
    </div>

<?php }
} ?>