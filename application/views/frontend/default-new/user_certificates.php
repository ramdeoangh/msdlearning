<?php $user_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array(); ?>
<?php include "breadcrumb.php"; ?>

<!-------- Certificate body section start ------>
<section class="wish-list-body message">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <?php include "profile_menus.php"; ?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="my-course-1-full-body">
                    <h4 class="text-black"><?php echo get_phrase('my_certificates'); ?></h4>
                    <p class="text-muted"><?php echo get_phrase('download_certificates_for_your_completed_courses'); ?>
                    </p>
                </div>

                <div class="row">
                    <?php if (!empty($enrolments)): ?>
                        <?php foreach ($enrolments as $enrolment):
                            $course_details = $this->crud_model->get_course_by_id($enrolment['course_id'])->row_array();
                            if (empty($course_details))
                                continue;

                            $course_progress = course_progress($course_details['id'], $this->session->userdata('user_id'));
                            $is_completed = ($course_progress >= 100);

                            // Check if certificate exists
                            $certificate_check = $this->db->get_where('certificates', array(
                                'course_id' => $course_details['id'],
                                'student_id' => $this->session->userdata('user_id')
                            ));
                            $has_certificate = ($certificate_check->num_rows() > 0);

                            if ($has_certificate) {
                                $certificate_data = $certificate_check->row_array();
                                $certificate_identifier = $certificate_data['shareable_url'];
                            }
                            ?>

                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $course_details['title']; ?></h5>
                                    <p class="card-text text-muted">
                                        <small><?php echo get_phrase('Progress'); ?>:
                                            <?php echo round($course_progress); ?>%</small>
                                    </p>
                                    <div class="progress mb-3" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: <?php echo $course_progress; ?>%"
                                            aria-valuenow="<?php echo $course_progress; ?>" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>

                                    <?php if ($is_completed): ?>
                                        <button type="button" class="btn btn-primary btn-sm get-certificate-btn"
                                            data-course-id="<?php echo $course_details['id']; ?>"
                                            data-certificate-id="<?php echo isset($certificate_identifier) ? $certificate_identifier : ''; ?>">
                                            <i class="fas fa-download me-2"></i><?php echo get_phrase('get_certificate'); ?>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-lock me-2"></i><?php echo get_phrase('course_not_completed'); ?>
                                        </button>
                                        <small
                                            class="text-muted d-block mt-2"><?php echo get_phrase('please_complete_the_course_first'); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i><?php echo get_phrase('No enrolled courses found'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        $('.get-certificate-btn').on('click', function () {
            var courseId = $(this).data('course-id');
            var certificateId = $(this).data('certificate-id');
            var button = $(this);

            // Check course completion first
            $.ajax({
                url: '<?php echo site_url("home/check_certificate_eligibility"); ?>',
                type: 'POST',
                data: { course_id: courseId },
                dataType: 'json',
                beforeSend: function () {
                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i><?php echo get_phrase("processing"); ?>...');
                },
                success: function (response) {
                    if (response.status == 'success') {
                        // Generate or download certificate
                        if (certificateId) {
                            // Certificate already exists, download it
                            window.location.href = '<?php echo site_url("home/download_certificate"); ?>/' + certificateId;
                        } else {
                            // Generate certificate first, then download
                            window.location.href = '<?php echo site_url("home/generate_and_download_certificate"); ?>/' + courseId;
                        }
                    } else {
                        alert(response.message || '<?php echo get_phrase("course_is_not_completed_please_complete_first"); ?>');
                        button.prop('disabled', false).html('<i class="fas fa-download me-2"></i><?php echo get_phrase("get_certificate"); ?>');
                    }
                },
                error: function () {
                    alert('<?php echo get_phrase("an_error_occurred_please_try_again"); ?>');
                    button.prop('disabled', false).html('<i class="fas fa-download me-2"></i><?php echo get_phrase("get_certificate"); ?>');
                }
            });
        });
    });
</script>