<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">
<script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
<script>
    var player = new Plyr('#player', {
        youtube: {
            // Options for YouTube player
            controls: 1, // Show YouTube controls
            modestBranding: false, // Show YouTube logo
            showinfo: 1, // Show video title and uploader on play
            rel: 0, // Show related videos at the end
            iv_load_policy: 3, // Do not show video annotations
            cc_load_policy: 1, // Show captions by default
            autoplay: false, // Do not autoplay
            loop: false, // Do not loop the video
            mute: false, // Do not mute the video
            start: 0, // Start at this time (in seconds)
            end: null // End at this time (in seconds)
        }
    });
</script>

<style type="text/css">
    .plyr__progress video {
        width: 180px !important;
        height: auto !important;
        position: absolute !important;
        bottom: 30px !important;
        z-index: 1 !important;
        border-radius: 10px !important;
        border: 2px solid #fff !important;
        display: none;
        background-color: #000;
    }

    .plyr__progress video:hover {
        display: none !important;
    }

    video:not(.plyr:fullscreen video) {
        width: 100%;
        max-height: auto !important;
        max-height: 567px !important;
        border-radius: 5px;
    }

    /* Overlay and progress bar styling */
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        visibility: hidden;
    }

    /* Circular progress bar container */
    .circular-progress-container {
        position: relative;
        width: 100px;
        height:100px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Outer circle border (for border effect) */
    .outer-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        stroke: #ddd; /* Border color */
        stroke-width: 7;
        fill: none;
    }

    /* Inner circle for progress animation */
    .circular-progress {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        stroke-dasharray: 440; /* Circumference of the circle */
        stroke-dashoffset: 440;
        stroke: #6610f2; /* Progress color */
        stroke-width: 7;
        fill: none;
        transition: stroke-dashoffset 5s linear;
    }

    .progress-ring {
        transform: rotate(-90deg); /* To start progress from the top */
    }

    .cancel-icon {
        position: absolute;
        top: 6px;
        right: 6px;
        cursor: pointer;
        background: #ff0000;
        color: #fff;
        font-size: 18px;
        height: 30px;
        width: 30px;
        line-height: 32px;
        border-radius: 50%;
        text-align: center;
    }
    .overlay-text {
        position: absolute;
        font-size: 16px;
        color: #ffffff;
        text-align: center;
        top: 70%;
        transform: translateY(-50%);
    }
</style>

<div class="overlay" id="nextVideoOverlay">
    <div class="circular-progress-container">
        <svg class="progress-ring" width="100" height="100">
            <!-- Outer Circle (border) -->
            <circle class="outer-circle" cx="50" cy="50" r="45" />
            <!-- Inner Circle (progress) -->
            <circle class="circular-progress" cx="50" cy="50" r="45" />
        </svg>
    </div>
    <div class="overlay-text">Playing next video in <span id="countdown">5</span> sec</div>
    <div class="cancel-icon" id="cancelNextVideo">✖</div>
</div>

<script type="text/javascript">
    // Define elements for overlay and countdown
    const overlay = document.getElementById('nextVideoOverlay');
    const countdownElement = document.getElementById('countdown');
    const cancelNextVideoButton = document.getElementById('cancelNextVideo');
    let countdownInterval;

    // Function to start countdown
    function startCountdown() {
        console.log('Starting countdown...');
        let countdown = 5; // Countdown set to 5 seconds
        countdownElement.textContent = countdown;
        overlay.style.visibility = 'visible';

        // Restart the circular progress animation
        const circleProgress = document.querySelector('.circular-progress');
        circleProgress.style.transition = 'none'; // Remove previous transition
        circleProgress.style.strokeDashoffset = 440; // Reset stroke offset
        setTimeout(() => {
            circleProgress.style.transition = 'stroke-dashoffset 5s linear';
            circleProgress.style.strokeDashoffset = 0; // Animate the circle fill to complete
        }, 10);

        countdownInterval = setInterval(() => {
            countdown -= 1;
            countdownElement.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                overlay.style.visibility = 'hidden';

                let lesson_id = '<?php echo $lesson_details['id']; ?>';
                let course_id = '<?php echo $course_details['id']; ?>';

                // Use stored next lesson ID
                if (nextLessonId && !pageRefreshed) {
                    pageRefreshed = true; // Prevent multiple redirects
                    const url = '<?php echo site_url("home/lesson/"); ?>' + '/' + '<?php echo slugify($course_details['title']); ?>' + '/' + course_id + '/' + nextLessonId;
                    console.log('Countdown complete - Redirecting to next lesson:', url);
                    console.log('Next lesson ID:', nextLessonId);
                    window.location.href = url; // Redirect to the next lesson
                } else {
                    console.log('No next lesson ID stored or already redirected');
                    console.log('nextLessonId:', nextLessonId, 'pageRefreshed:', pageRefreshed);
                }
            }

        }, 1000);
    }

    // Event listener for video end
    if (typeof player === 'object' && player !== null) {
        player.on('ended', () => {
            console.log('Video has ended - Starting completion check...');
            
            // Force update watch history to ensure lesson completion is recorded
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('home/update_watch_history_with_duration'); ?>',
                data: {
                    lesson_id: lesson_id,
                    course_id: course_id,
                    current_duration: player.duration // Use full video duration
                },
                success: function(response) {
                    try {
                        var responseVal = JSON.parse(response);
                        console.log('Final watch history update:', responseVal);
                        
                        if (responseVal.is_completed == 1 && !pageRefreshed) {
                            console.log('Lesson marked as completed on video end');
                            
                            // Get next lesson dynamically via AJAX
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo site_url('home/get_next_lesson'); ?>',
                                data: {
                                    course_id: course_id,
                                    current_lesson_id: lesson_id
                                },
                                success: function(nextResponse) {
                                    try {
                                        var nextData = JSON.parse(nextResponse);
                                        console.log('Next lesson response:', nextData);
                                        console.log('Raw next lesson response:', nextResponse);
                                        
                                        if (nextData.next_lesson_id && nextData.next_lesson_id != 'null') {
                                            console.log('Next lesson found:', nextData.next_lesson_id);
                                            nextLessonId = nextData.next_lesson_id; // Store for countdown
                                            console.log('Starting countdown for next lesson...');
                                            startCountdown(); // Start showing countdown when video ends
                                        } else {
                                            // No next lesson, just refresh to update progress
                                            console.log('No next lesson available, refreshing page to update progress...');
                                            setTimeout(function() {
                                                location.reload();
                                            }, 1000);
                                        }
                                    } catch (e) {
                                        console.error('Error parsing next lesson response:', e);
                                        // Fallback: refresh page
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1000);
                                    }
                                },
                                error: function() {
                                    console.error('Error getting next lesson');
                                    // Fallback: refresh page
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            });
                        } else if (responseVal.is_completed == 1) {
                            console.log('Lesson already completed, no action needed');
                        } else {
                            console.log('Lesson not marked as completed. Progress:', responseVal.course_progress);
                        }
                    } catch (e) {
                        console.error('Error parsing final watch history response:', e);
                        console.log('Raw response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error in final watch history update:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                }
            });
        });
    }

    // Cancel next video if user clicks cancel icon
    cancelNextVideoButton.addEventListener('click', () => {
        clearInterval(countdownInterval);
        overlay.style.visibility = 'hidden';
        console.log('Next video playback canceled');
    });
</script>

<!-- Update Watch history and set current duration-->
<script type="text/javascript">
    let lesson_id = '<?php echo $lesson_details['id']; ?>';
    let course_id = '<?php echo $course_details['id']; ?>';
    var currentProgress = '<?php echo lesson_progress($lesson_details['id']); ?>';
    let previousSavedDuration = 0;
    let currentDuration = 0;
    let lessonCompleted = false; // Flag to prevent multiple refreshes
    let pageRefreshed = false; // Flag to prevent multiple page refreshes
    let nextLessonId = null; // Store next lesson ID for countdown
    
    // Check if lesson is already completed on page load
    <?php 
    $watch_history = $this->crud_model->get_watch_histories($this->session->userdata('user_id'), $course_details['id'])->row_array();
    $completed_lessons = array();
    if(is_array($watch_history) && !empty($watch_history['completed_lesson'])) {
        $completed_lessons = json_decode($watch_history['completed_lesson'], true);
    }
    $is_already_completed = in_array($lesson_details['id'], $completed_lessons);
    ?>
    <?php if($is_already_completed): ?>
        lessonCompleted = true; // Lesson is already completed
        console.log('Lesson is already completed');
    <?php endif; ?>

    if (typeof player === 'object' && player !== null) {
        setInterval(function() {
            currentDuration = parseInt(player.currentTime);
            if (lesson_id && course_id && (currentDuration % 5) == 0 && previousSavedDuration != currentDuration && !lessonCompleted) {
                previousSavedDuration = currentDuration;

                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('home/update_watch_history_with_duration'); ?>',
                    data: {
                        lesson_id: lesson_id,
                        course_id: course_id,
                        current_duration: currentDuration
                    },
                    success: function(response) {
                        try {
                            var responseVal = JSON.parse(response);
                            console.log('Watch history update response:', responseVal);
                            console.log('Course progress:', responseVal.course_progress);
                            console.log('Lesson completed:', responseVal.is_completed);
                            
                            // Only set completion flag, don't refresh during video playback
                            if (responseVal.is_completed == 1 && !lessonCompleted) {
                                lessonCompleted = true;
                                console.log('Lesson marked as completed during playback');
                            }
                        } catch (e) {
                            console.error('Error parsing watch history response:', e);
                            console.log('Raw response:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error updating watch history:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                    }
                });
            }
        }, 900);
    }

    // Play from previous duration
    <?php $student_id = $this->session->userdata('user_id'); ?>
    <?php $watched_duration = $this->db->get_where('watched_duration', ['watched_lesson_id' => $lesson_details['id'], 'watched_student_id' => $student_id])->row_array(); ?>
    var previous_duration = <?php echo isset($watched_duration['current_duration']) && $watched_duration['current_duration'] > 0 ? $watched_duration['current_duration'] : 0; ?>;
    var previousTimeSetter = setInterval(function() {
        if (player.playing == false && player.currentTime != previous_duration) {
            player.currentTime = previous_duration;
            console.log(previous_duration);
            console.log(player.currentTime);
        } else {
            clearInterval(previousTimeSetter);
        }
    }, 200);

    $(document).ready(function() {
        setTimeout(function(){
            $('.remove_video_src').remove();
        }, 2000);
    });
</script>
