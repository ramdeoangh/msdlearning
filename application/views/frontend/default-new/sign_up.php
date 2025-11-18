<?php if(get_frontend_settings('recaptcha_status')): ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<!---------- Header Section End  ---------->
<section class="sign-up my-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12 col-12 text-center">
                <img loading="lazy" width="65%" src="<?php echo site_url('assets/frontend/default-new/image/login-security.gif') ?>">
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12 col-12 ">
                <div class="sing-up-right">
                    <h3><?php echo get_phrase('Sign Up'); ?><span>!</span></h3>
                    <p><?php echo get_phrase('Explore, learn, and grow with us. Enjoy a seamless and enriching educational journey. Lets begin!') ?></p>

                    <form action="<?php echo site_url('login/register') ?>" method="post" enctype="multipart/form-data" id="signup-form">
                        <div class="row">
                            <!-- First Name and Last Name - Side by Side -->
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('First Name'); ?></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-user"></i>
                                    <input class="form-control" id="first_name" type="text" name="first_name" placeholder="<?php echo get_phrase('Enter your first name'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('Last Name'); ?></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-user"></i>
                                    <input class="form-control" id="last_name" type="text" name="last_name" placeholder="<?php echo get_phrase('Enter your last name'); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email and Password - Side by Side -->
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('Your email'); ?></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-user"></i>
                                    <input class="form-control" id="email" type="email" name="email" placeholder="<?php echo get_phrase('Enter your email'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('Password') ?></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-key"></i>
                                    <i class="fa-solid fas fa-eye cursor-pointer" onclick="if($('#password').attr('type') == 'text'){$('#password').attr('type', 'password');}else{$('#password').attr('type', 'text');} $(this).toggleClass('fa-eye'); $(this).toggleClass('fa-eye-slash') " style="right: 20px; left: unset;"></i>
                                    <input class="form-control" id="password" type="password" name="password" placeholder="<?php echo get_phrase('Enter your valid password'); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Location and Designation - Side by Side -->
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('select_workplace'); ?> <span class="text-danger">*</span></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <select class="form-control" id="location" name="location" required>
                                        <option value=""><?php echo get_phrase('select_workplace'); ?></option>
                                        <option value="PHC">PHC</option>
                                        <option value="Sub-PHC">Sub-PHC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('Designation'); ?> <span class="text-danger">*</span></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-briefcase"></i>
                                    <select class="form-control" id="designation" name="designation" required>
                                        <option value=""><?php echo get_phrase('Select Designation'); ?></option>
                                        <option value="ANM">ANM</option>
                                        <option value="MPW">MPW</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Phone Number and City - Side by Side -->
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('Phone Number'); ?> <span class="text-danger">*</span></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-phone"></i>
                                    <input class="form-control" id="phone" type="tel" name="phone" placeholder="<?php echo get_phrase('Enter your phone number'); ?>" pattern="[0-9]{10}" maxlength="10" required>
                                </div>
                            </div>
                        

                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('State'); ?> <span class="text-danger">*</span></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-map"></i>
                                    <select class="form-control" id="state" name="state" required>
                                        <option value=""><?php echo get_phrase('select_state'); ?></option>
                                        <?php 
                                        $indian_states = get_indian_states();
                                        asort($indian_states); // Sort alphabetically
                                        foreach($indian_states as $code => $state): 
                                        ?>
                                            <option value="<?php echo html_escape($state); ?>"><?php echo html_escape($state); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- City and Pincode - Side by Side -->
                            <div class="col-md-6 mb-3" id="city_dropdown_container">
                                <h5><?php echo get_phrase('City'); ?> <span class="text-danger">*</span></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-city"></i>
                                    <select class="form-control" id="city" name="city">
                                        <option value=""><?php echo get_phrase('select_city'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5><?php echo get_phrase('Pincode'); ?> <span class="text-danger">*</span></h5>
                                <div class="position-relative">
                                    <i class="fa-solid fa-mail-bulk"></i>
                                    <input class="form-control" id="pincode" type="text" name="pincode" placeholder="<?php echo get_phrase('Enter your pincode'); ?>" pattern="[0-9]{6}" maxlength="6" required>
                                </div>
                            </div>
                        </div>

                        <!-- <?php if(get_settings('allow_instructor')): ?>
                            <div class="mb-4">
                                <input id="instructor" type="checkbox" onchange="$('#become-instructor-fields').toggle()" name="instructor" value="yes" <?php echo isset($_GET['instructor']) ? 'checked':''; ?>>
                                <label for="instructor"><?php echo get_phrase('Apply to Become an instructor'); ?></label>
                            </div>

                            <div id="become-instructor-fields" class="<?php echo isset($_GET['instructor']) ?  '':'d-hidden'; ?>">
                                <div class="mb-4">
                                    <h5><?php echo get_phrase('Phone'); ?></h5>
                                    <div class="position-relative">
                                        <i class="fas fa-phone"></i>
                                        <input class="form-control" id="phone" type="phone" name="phone" placeholder="<?php echo get_phrase('Enter your phone number'); ?>">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <h5><?php echo get_phrase('Document'); ?> <small>(doc, docs, pdf, txt, png, jpg, jpeg)</small></h5>
                                    <div class="position-relative">
                                        <input class="form-control" id="document" type="file" name="document">
                                        <small><?php echo get_phrase('Provide some documents about your qualifications'); ?></small>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <h5><?php echo get_phrase('message'); ?></h5>
                                    <div class="position-relative">
                                        <textarea class="form-control" name="message" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?> -->

                        <?php if(get_frontend_settings('recaptcha_status')): ?>
                            <div class="g-recaptcha" data-sitekey="<?php echo get_frontend_settings('recaptcha_sitekey'); ?>"></div>
                        <?php endif; ?>

                        <?php if(get_frontend_settings('recaptcha_status_v3')): ?>
                        <div class="log-in">
                            <button class="btn btn-primary g-recaptcha" data-sitekey="<?php echo get_frontend_settings('recaptcha_sitekey_v3'); ?>" data-callback='onSignupSubmit' data-action='submit'>
                                <?php echo get_phrase('Sign Up'); ?>
                            </button>
                        </div>
                        <?php else: ?>
                        
                        <div class="log-in">
                            <button type="submit" class="btn btn-primary">
                                <?php echo get_phrase('Sign Up') ?>
                            </button>
                        </div>
                        <?php endif; ?>
                    </form>

                    <div class="another text-center">
                        <p>
                            <?php echo get_phrase('Already you have an account?') ?>
                            <a href="<?php echo site_url('login') ?>"><?php echo get_phrase('Log In') ?></a>
                        </p>
                        <h5><?php echo get_phrase('Or') ?></h5>
                    </div>
                    <div class="social-media">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <!-- <button type="button" class="btn btn-primary"><a href="#"><img loading="lazy" src="image/facebook.png"> Facebook</a></button> -->
                                <?php if(get_settings('fb_social_login')) include "facebook_login.php"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function onSignupSubmit(token) {
        document.getElementById("signup-form").submit();
    }

    // Handle state selection and city dropdown
    document.addEventListener('DOMContentLoaded', function() {
        // Cities data for Maharashtra
        const stateCities = {
            'Maharashtra': ['Nandurbar', 'Palghar', 'Washim']
        };

        const stateSelect = document.getElementById('state');
        const cityContainer = document.getElementById('city_dropdown_container');
        const citySelect = document.getElementById('city');

        if (stateSelect) {
            stateSelect.addEventListener('change', function() {
                const selectedState = this.value;
                
                // Clear previous city options
                citySelect.innerHTML = '<option value=""><?php echo get_phrase('select_city'); ?></option>';
                
                // Show/hide city dropdown based on state selection
                if (selectedState && stateCities[selectedState]) {
                    // Populate cities for the selected state
                    stateCities[selectedState].forEach(function(city) {
                        const option = document.createElement('option');
                        option.value = city;
                        option.textContent = city;
                        citySelect.appendChild(option);
                    });
                    
                    citySelect.setAttribute('required', 'required');
                } else {
                    citySelect.removeAttribute('required');
                    citySelect.value = '';
                }
            });
        }
    });
</script>