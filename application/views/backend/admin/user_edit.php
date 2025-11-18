<?php
    $user_data = $this->db->get_where('users', array('id' => $user_id))->row_array();
        
    $social_links = json_decode($user_data['social_links'], true);
    
    // Ensure social_links is an array to avoid warnings
    if (!is_array($social_links)) {
        $social_links = array(
            'facebook' => '',
            'twitter' => '',
            'linkedin' => ''
        );
    }
   
    $payment_keys = json_decode($user_data['payment_keys'], true);
    
    // Ensure payment_keys is an array
    if (!is_array($payment_keys)) {
        $payment_keys = array();
    }
 
    // Handle empty payment_keys array to avoid warnings
    $paypal_keys = isset($payment_keys['paypal']) && is_array($payment_keys['paypal']) ? $payment_keys['paypal'] : array();
    $stripe_keys = isset($payment_keys['stripe']) && is_array($payment_keys['stripe']) ? $payment_keys['stripe'] : array();
    $razorpay_keys = isset($payment_keys['razorpay']) && is_array($payment_keys['razorpay']) ? $payment_keys['razorpay'] : array();
?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo $page_title; ?> </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3"><?php echo get_phrase('student_edit_form'); ?></h4>

                <form class="required-form" action="<?php echo site_url('admin/users/edit/'.$user_id); ?>" enctype="multipart/form-data" method="post">
                    <div id="progressbarwizard">
                        <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                            <li class="nav-item">
                                <a href="#basic_info" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-face-profile mr-1"></i>
                                    <span class="d-none d-sm-inline"><?php echo get_phrase('basic_info'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#login_credentials" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-lock mr-1"></i>
                                    <span class="d-none d-sm-inline"><?php echo get_phrase('login_credentials'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#social_information" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-wifi mr-1"></i>
                                    <span class="d-none d-sm-inline"><?php echo get_phrase('social_information'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#payment_info" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-currency-eur mr-1"></i>
                                    <span class="d-none d-sm-inline"><?php echo get_phrase('payment_info'); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#finish" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                    <i class="mdi mdi-checkbox-marked-circle-outline mr-1"></i>
                                    <span class="d-none d-sm-inline"><?php echo get_phrase('finish'); ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content b-0 mb-0">

                            <div id="bar" class="progress mb-3" style="height: 7px;">
                                <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"></div>
                            </div>

                            <div class="tab-pane" id="basic_info">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="first_name"><?php echo get_phrase('first_name'); ?> <span class="required">*</span> </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user_data['first_name']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="last_name"><?php echo get_phrase('last_name'); ?> <span class="required">*</span> </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user_data['last_name']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="linkedin_link"><?php echo get_phrase('biography'); ?></label>
                                            <div class="col-md-9">
                                                <textarea name="biography" id = "summernote-basic" class="form-control"><?php echo $user_data['biography']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="address"><?php echo get_phrase('address'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="<?php echo $user_data['address']; ?>" id="address" name="address">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="location"><?php echo get_phrase('Location'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <select class="form-control" id="location" name="location" required>
                                                    <option value=""><?php echo get_phrase('Select Location'); ?></option>
                                                    <option value="PHC" <?php echo (isset($user_data['location']) && $user_data['location'] == 'PHC') ? 'selected' : ''; ?>>PHC</option>
                                                    <option value="Sub-PHC" <?php echo (isset($user_data['location']) && $user_data['location'] == 'Sub-PHC') ? 'selected' : ''; ?>>Sub-PHC</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="designation"><?php echo get_phrase('Designation'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <select class="form-control" id="designation" name="designation" required>
                                                    <option value=""><?php echo get_phrase('Select Designation'); ?></option>
                                                    <option value="ANM" <?php echo (isset($user_data['designation']) && $user_data['designation'] == 'ANM') ? 'selected' : ''; ?>>ANM</option>
                                                    <option value="MPW" <?php echo (isset($user_data['designation']) && $user_data['designation'] == 'MPW') ? 'selected' : ''; ?>>MPW</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="phone"><?php echo get_phrase('Phone Number'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="tel" class="form-control" value="<?php echo isset($user_data['phone']) ? $user_data['phone'] : ''; ?>" id="phone" name="phone" pattern="[0-9]{10}" maxlength="10" required>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="city"><?php echo get_phrase('City'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="<?php echo isset($user_data['city']) ? $user_data['city'] : ''; ?>" id="city" name="city" required>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="state"><?php echo get_phrase('State'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <select class="form-control" id="state" name="state" required>
                                                    <option value=""><?php echo get_phrase('Select State'); ?></option>
                                                    <?php 
                                                    $this->load->helper('indian_states');
                                                    $indian_states = get_indian_states();
                                                    asort($indian_states);
                                                    $selected_state = isset($user_data['state']) ? $user_data['state'] : '';
                                                    foreach($indian_states as $code => $state): 
                                                    ?>
                                                        <option value="<?php echo html_escape($state); ?>" <?php echo ($selected_state == $state) ? 'selected' : ''; ?>><?php echo html_escape($state); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="pincode"><?php echo get_phrase('Pincode'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="<?php echo isset($user_data['pincode']) ? $user_data['pincode'] : ''; ?>" id="pincode" name="pincode" pattern="[0-9]{6}" maxlength="6" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="user_image"><?php echo get_phrase('user_image'); ?></label>
                                            <div class="col-md-9">
                                                <div class="d-flex">
                                                  <div class="">
                                                      <img class = "rounded-circle img-thumbnail" src="<?php echo $this->user_model->get_user_image_url($user_data['id']);?>" alt="" style="height: 50px; width: 50px;">
                                                  </div>
                                                  <div class="flex-grow-1 mt-1 pl-3">
                                                      <div class="input-group">
                                                          <div class="custom-file">
                                                              <input type="file" class="custom-file-input" name = "user_image" id="user_image" onchange="changeTitleOfImageUploader(this)" accept="image/*">
                                                              <label class="custom-file-label ellipsis" for="user_image"><?php echo get_phrase('choose_user_image'); ?></label>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div>

                            <div class="tab-pane" id="login_credentials">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="email"> <?php echo get_phrase('email'); ?> <span class="required">*</span> </label>
                                            <div class="col-md-9">
                                                <input type="email" id="email" name="email" class="form-control" value="<?php echo $user_data['email']; ?>" required>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div>

                            <div class="tab-pane" id="social_information">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="facebook_link"> <?php echo get_phrase('facebook'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" id="facebook_link" name="facebook_link" class="form-control" value="<?php echo isset($social_links['facebook']) ? $social_links['facebook'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="twitter_link"><?php echo get_phrase('twitter'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" id="twitter_link" name="twitter_link" class="form-control" value="<?php echo isset($social_links['twitter']) ? $social_links['twitter'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label" for="linkedin_link"><?php echo get_phrase('linkedin'); ?></label>
                                            <div class="col-md-9">
                                                <input type="text" id="linkedin_link" name="linkedin_link" class="form-control" value="<?php echo isset($social_links['linkedin']) ? $social_links['linkedin'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div>
                            
                            <div class="tab-pane" id="payment_info">
                                <div class="row">
                                    <div class="col-12">
                                        <?php $payment_gateways = $this->db->get('payment_gateways')->result_array();
                                            foreach($payment_gateways as $key => $payment_gateway):
                                            $keys = json_decode($payment_gateway['keys'], true);
                                            $user_keys = json_decode($user_data['payment_keys'], true);
                                            
                                            // Ensure user_keys is an array to avoid warnings
                                            if (!is_array($user_keys)) {
                                                $user_keys = array();
                                            }
                                            
                                            ?>
                                            <div class="<?php if($payment_gateway['status'] != 1 || !addon_status($payment_gateway['identifier']) && $payment_gateway['is_addon'] == 1) echo 'd-none'; ?>">
                                                <h4><?php echo get_phrase($payment_gateway['title']); ?></h4>
                                                <?php foreach($keys as $index => $value):
                                                    if(is_array($user_keys) && array_key_exists($payment_gateway['identifier'], $user_keys)){
                                                        if(is_array($user_keys[$payment_gateway['identifier']]) && array_key_exists($index, $user_keys[$payment_gateway['identifier']])){
                                                            $value = $user_keys[$payment_gateway['identifier']][$index];
                                                        }else{
                                                            $value = '';
                                                        }
                                                    }else{
                                                        $value = '';
                                                    }
                                                    ?>

                                                    <div class="form-group row mb-3">
                                                        <label class="col-md-3 col-form-label" for="<?php echo $payment_gateway['identifier'].$index; ?>"> <?php echo get_phrase($index); ?></label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="<?php echo $payment_gateway['identifier'].$index; ?>" name="gateways[<?php echo $payment_gateway['identifier']; ?>][<?php echo $index; ?>]" value="<?php echo $value; ?>" class="form-control">
                                                            <small><?php echo get_phrase("required_for_instructor"); ?></small>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <hr>
                                            </div>
                                        <?php endforeach; ?>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div>
                            
                            <div class="tab-pane" id="finish">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                                            <h3 class="mt-0"><?php echo get_phrase('thank_you'); ?> !</h3>

                                            <p class="w-75 mb-2 mx-auto"><?php echo get_phrase('you_are_just_one_click_away'); ?></p>

                                            <div class="mb-3">
                                                <button type="button" class="btn btn-primary" onclick="checkRequiredFields()" name="button"><?php echo get_phrase('submit'); ?></button>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div>

                            <ul class="list-inline mb-0 wizard">
                                <li class="previous list-inline-item">
                                    <a href="javascript:;" class="btn btn-info">Previous</a>
                                </li>
                                <li class="next list-inline-item float-right">
                                    <a href="javascript:;" class="btn btn-info">Next</a>
                                </li>
                            </ul>

                        </div> <!-- tab-content -->
                    </div> <!-- end #progressbarwizard-->
                </form>

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div>
</div>
