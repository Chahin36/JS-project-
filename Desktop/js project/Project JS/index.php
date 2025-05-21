<?php 
session_start();
$registration_success = isset($_GET['registration']) && $_GET['registration'] === 'success';

?>

<!DOCTYPE html>
<html lang="en">

  <head>
    
    <!-- Required CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Required JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <title>JS Project</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!--

TemplateMo 570 Chain App Dev

https://templatemo.com/tm-570-chain-app-dev

-->

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/templatemo-chain-app-dev.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">

  </head>

<body>
<script>
$(document).ready(function() {
  // Make buttons more responsive
  $('.admin-actions .btn').on('mousedown', function() {
        $(this).addClass('active-state');
    }).on('mouseup mouseleave', function() {
        $(this).removeClass('active-state');
    });
    // Edit button click
    $(document).on('click', '.edit-item', function() {
        const id = $(this).data('id');
        
        $.get('get_pricing_item.php?id=' + id, function(data) {
            if(data.error) {
                alert(data.error);
                return;
            }
            
            $('#edit_id').val(data.id);
            $('#edit_title').val(data.title);
            $('#edit_image').val(data.image_path);
            $('#edit_description').val(data.description);
            $('#edit_features').val(data.features.replace(/<br\s*\/?>/g, "\n"));
            $('#editModal').modal('show');
        }, 'json').fail(function() {
            alert('Failed to load item data');
        });
    });
    
    // Modify button click (same as edit in this case)
    $(document).on('click', '.modify-item', function() {
        const id = $(this).data('id');
        
        $.get('get_pricing_item.php?id=' + id, function(data) {
            if(data.error) {
                alert(data.error);
                return;
            }
            
            $('#edit_id').val(data.id);
            $('#edit_title').val(data.title);
            $('#edit_image').val(data.image_path);
            $('#edit_description').val(data.description);
            $('#edit_features').val(data.features.replace(/<br\s*\/?>/g, "\n"));
            $('#editModal').modal('show');
        }, 'json').fail(function() {
            alert('Failed to load item data');
        });
    });
    
    // Save edited item
    $('#editForm').submit(function(e) {
        e.preventDefault();
        
        // Convert newlines in features to <br> tags
        let features = $('#edit_features').val();
        features = features.replace(/\n/g, "<br>");
        $('#edit_features').val(features);
        
        $.post('update_pricing_item.php', $(this).serialize(), function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        }, 'json').fail(function() {
            alert('Failed to update item');
        });
    });
    
    // Delete button click
    $(document).on('click', '.delete-item', function() {
        if(confirm('Are you sure you want to delete this item?')) {
            const id = $(this).data('id');
            
            $.post('delete_pricing_item.php', {id: id}, function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            }, 'json').fail(function() {
                alert('Failed to delete item');
            });
        }
    });
});
$(document).ready(function() {
    // Add New Item button click
    $('#addNewItem').click(function() {
        // Show the modal
        $('#addModal').modal('show');
        
        // Reset form
        $('#addForm')[0].reset();
        $('#add_id').val('0'); // 0 indicates new item
    });

    // Form submission
    $('#addForm').submit(function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        
        // Show loading state
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        // Submit via AJAX
        $.ajax({
            url: 'add_pricing_item.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json'
        }).done(function(response) {
            if(response.success) {
                // Close modal
                $('#addModal').modal('hide');
                
                // Reload the page
                location.reload();
                
                
                Swal.fire('Success!', 'New pricing item added successfully', 'success');
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        }).fail(function(xhr) {
            Swal.fire('Error!', 'Request failed: ' + xhr.statusText, 'error');
        }).always(function() {
            $submitBtn.prop('disabled', false).html('Save Item');
        });
    });
});
</script>
<script>
  // Newsletter Subscription
 document.addEventListener('DOMContentLoaded', function() {
    const subscribeForm = document.getElementById('subscribeForm');
    
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(subscribeForm);
            
            fetch('subscribe.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    subscribeForm.reset();
                    // Reload the page after successful subscription
                    location.reload();
                } else if (data.status === 'exists') {
                    alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    }
});
</script>
  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <a href="index.php" class="logo">
              <img src="logo.png" alt="Chain App Dev">
            </a>
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
              <li class="scroll-to-section"><a href="#services">Services</a></li>
              <li class="scroll-to-section"><a href="#about">About</a></li>
              <li class="scroll-to-section"><a href="#pricing">Pricing</a></li>
              <li class="scroll-to-section"><a href="#newsletter">Newsletter</a></li>
              <?php if(isset($_SESSION['user_id'])): ?>
                <li class="user-menu">
                  <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                      <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a href="logout.php">Logout</a></li>
                    </ul>
                  </div>
                </li>
              <?php else: ?>
                <li><div class="gradient-button"><a id="modal_trigger" href="#modal"><i class="fa fa-sign-in-alt"></i> Sign In Now</a></div></li>
              <?php endif; ?>
            </ul>        
            <a class='menu-trigger'>
                <span>Menu</span>
            </a>
            <!-- ***** Menu End ***** -->
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->
  
  <div id="modal" class="popupContainer" style="display:none;">
    <div class="popupHeader">
        <span class="header_title">Login</span>
        <span class="modal_close"><i class="fa fa-times"></i></span>
    </div>

    <section class="popupBody">

        <!-- Social Login -->
        <div class="social_login">
            <div class="">
                <a href="#" class="social_box fb">
                    <span class="icon"><i class="fab fa-facebook"></i></span>
                    <span class="icon_title">Connect with Facebook</span>
                </a>

                <a href="#" class="social_box google">
                    <span class="icon"><i class="fab fa-google-plus"></i></span>
                    <span class="icon_title">Connect with Google</span>
                </a>
            </div>

            <div class="centeredText">
                <span>Or use your Email address</span>
            </div>

            <div class="action_btns">
                <div class="one_half"><a href="#" id="login_form" class="btn">Login</a></div>
                <div class="one_half last"><a href="#" id="register_form" class="btn">Sign up</a></div>
            </div>
        </div>

        <!-- Username & Password Login form -->
        <div class="user_login">
            <form action="login.php" method="POST">
                <label>Email / Username</label>
                <input type="text" name="email_username" required />
                <br />

                <label>Password</label>
                <input type="password" name="password" required />
                <br />

                <div class="checkbox">
                    <input id="remember" type="checkbox" name="remember" />
                    <label for="remember">Remember me on this computer</label>
                </div>

                <div class="action_btns">
                    <div class="one_half"><a href="#" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Back</a></div>
                    <div class="one_half last"><button type="submit" class="btn btn_red">Login</button></div>
                </div>
            </form>

            <a href="#" class="forgot_password">Forgot password?</a>
        </div>

        <!-- Register Form -->
        <div class="user_register">
            <form action="register.php" method="POST">
                <label>Full Name</label>
                <input type="text" name="full_name" required />
                <br />

                <label>Email Address</label>
                <input type="email" name="email" required />
                <br />

                <label>Password</label>
                <input type="password" name="password" required />
                <br />

                <div class="checkbox">
                    <input id="send_updates" type="checkbox" name="updates" value="1" />
                    <label for="send_updates">Send me occasional email updates</label>
                </div>

                <div class="action_btns">
                    <div class="one_half"><a href="#" class="btn back_btn"><i class="fa fa-angle-double-left"></i> Back</a></div>
                    <div class="one_half last"><button type="submit" class="btn btn_red">Register</button></div>
                </div>
            </form>
        </div>

    </section>
</div>


  <div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-6 align-self-center">
              <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                <div class="row">
                  <div class="col-lg-12">
                    <h2>Get The Latest App From App Stores</h2>
                    <p>TunisConnect is your all-in-one telecom companion.
                      Check your balance, manage data, and recharge in seconds.
                      Simple, fast, and made for Tunisia.</p>
                  </div>
                  <div class="col-lg-12">
                    <div class="white-button first-button scroll-to-section">
                      <a href="#contact">App Store <i class="fab fa-apple"></i></a>
                    </div>
                    <div class="white-button scroll-to-section">
                      <a href="#contact">Play Store <i class="fab fa-google-play"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                <img src="interface.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="services" class="services section">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="section-heading  wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
            <h4>Amazing <em>Services &amp; Features</em> for you</h4>
            <img src="assets/images/heading-line-dec.png" alt="">
            <p>Discover powerful tools designed to simplify your telecom experience.
              From instant recharges to personalized offers, everything is just a tap away.
              Enjoy seamless, smart, and secure services — built for your daily needs.
              
              
              
              
              
              
              
              </p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <div class="service-item first-service">
            <div class="icon"></div>
            <h4>Balance & Usage Tracking</h4>
            <p>Easily view your current balance and usage history.
              Stay informed with real-time updates on calls, SMS, and data.
              No more surprises—just clear, simple stats.</p>
            <div class="text-button">
              <a href="#">Read More <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="service-item second-service">
            <div class="icon"></div>
            <h4>Instant Recharge</h4>
            <p>Recharge your phone anytime, anywhere in seconds.
              Supports multiple payment methods for convenience.
              Fast, secure, and always available.</p>
            <div class="text-button">
              <a href="#">Read More <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="service-item third-service">
            <div class="icon"></div>
            <h4>Exclusive Offers & Packages</h4>
            <p>Browse and activate the latest data and call offers.
              Enjoy special bundles made just for you.
              Save more with personalized telecom deals.
              
               <a rel="nofollow" href="https://paypal.me/templatemo" target="_blank">a little via PayPal</a>. Thank you.</p>
            <div class="text-button">
              <a href="#">Read More <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="service-item fourth-service">
            <div class="icon"></div>
            <h4>24/7 Help &amp; Support</h4>
            <p>Get assistance anytime through live chat or FAQs.
              Our support team is here day and night to help you.
              Quick answers, real people, zero stress.</p>
            <div class="text-button">
              <a href="#">Read More <i class="fa fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="about" class="about-us section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 align-self-center">
          <div class="section-heading">
            <h4>About <em>What We Do</em> &amp; Who We Are</h4>
            <img src="assets/images/heading-line-dec.png" alt="">
            <p>At TunisConnect, we’re redefining how Tunisia connects.
              Our mission is to provide fast, reliable, and user-friendly telecom solutions.
              We're a passionate team focused on innovation, simplicity, and customer care.</p>
          </div>
          
        </div>
        <div class="col-lg-6">
          <div class="right-image">
            
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="clients" class="the-clients">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="section-heading">
            <h4>Check What <em>The Clients Say</em> About Our App Dev</h4>
            <img src="assets/images/heading-line-dec.png" alt="">
            <p>Hear directly from our users and their experiences with TunisConnect.
              Real stories, honest feedback, and trusted opinions.
              See why thousands choose our app every day.</p>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="naccs">
            <div class="grid">
              <div class="row">
                <div class="col-lg-7 align-self-center">
                  <div class="menu">
                    <div class="first-thumb active">
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-4 col-sm-4 col-12">
                            <h4>Hamza Jbeli CEO</h4>
                            <span class="date">30 November 2021</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                            <span class="category">Financial Apps</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <span class="rating">4.8</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-4 col-sm-4 col-12">
                            <h4>Ilyes Kharroubi</h4>
                            <span class="date">29 November 2021</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                            <span class="category">Digital Business</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <span class="rating">4.5</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-4 col-sm-4 col-12">
                            <h4>May Catherina</h4>
                            <span class="date">27 November 2021</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                            <span class="category">Business &amp; Economics</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <span class="rating">4.7</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-4 col-sm-4 col-12">
                            <h4>Random User</h4>
                            <span class="date">24 November 2021</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                            <span class="category">New App Ecosystem</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <span class="rating">3.9</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="last-thumb">
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-4 col-sm-4 col-12">
                            <h4>Mark Amber Do</h4>
                            <span class="date">21 November 2021</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                            <span class="category">Web Development</span>
                          </div>
                          <div class="col-lg-4 col-sm-4 col-12">
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <i class="fa fa-star"></i>
                              <span class="rating">4.3</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 
                <div class="col-lg-5">
                  <ul class="nacc">
                    <li class="active">
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="assets/images/quote.png" alt="">
                                <p>“TunisConnect made managing my mobile account so easy! I can recharge, check my balance, and activate offers in just a few taps. Super smooth and reliable—exactly what I needed!”</p>
                              </div>
                              <div class="down-content">
                                <img src="hamza.jpg" alt="">
                                <div class="right-content">
                                  <h4>Hamza Jbeli</h4>
                                  <span>CEO of Jbeli Company</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="assets/images/quote.png" alt="">
                                <p>“I love how simple and clean the app is. The 24/7 support actually helped me solve an issue within minutes. It really feels like the app was made for Tunisians!”</p>
                              </div>
                              <div class="down-content">
                                <img src="ilyes.jpg" alt="">
                                <div class="right-content">
                                  <h4>Ilyes Kharroubi</h4>
                                  <span>CTO of Digital Company</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="assets/images/quote.png" alt="">
                                <p>“May, Lorem ipsum dolor sit amet, consectetur adpiscing elit, sed do eismod tempor idunte ut labore et dolore magna aliqua darwin kengan
                                  lorem ipsum dolor sit amet, consectetur picing elit massive big blasta.”</p>
                              </div>
                              <div class="down-content">
                                <img src="assets/images/client-image.jpg" alt="">
                                <div class="right-content">
                                  <h4>May C.</h4>
                                  <span>Founder of Catherina Co.</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="assets/images/quote.png" alt="">
                                <p>“Lorem ipsum dolor sit amet, consectetur adpiscing elit, sed do eismod tempor idunte ut labore et dolore magna aliqua darwin kengan
                                  lorem ipsum dolor sit amet, consectetur picing elit massive big blasta.”</p>
                              </div>
                              <div class="down-content">
                                <img src="assets/images/client-image.jpg" alt="">
                                <div class="right-content">
                                  <h4>Random Staff</h4>
                                  <span>Manager, Digital Company</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="thumb">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="client-content">
                                <img src="assets/images/quote.png" alt="">
                                <p>“Mark, Lorem ipsum dolor sit amet, consectetur adpiscing elit, sed do eismod tempor idunte ut labore et dolore magna aliqua darwin kengan
                                  lorem ipsum dolor sit amet, consectetur picing elit massive big blasta.”</p>
                              </div>
                              <div class="down-content">
                                <img src="assets/images/client-image.jpg" alt="">
                                <div class="right-content">
                                  <h4>Mark Am</h4>
                                  <span>CTO, Amber Do Company</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>          
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="pricing" class="pricing-tables">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="section-heading">
                    <h4>We Have The Best Pre-Order <em>Prices</em> You Can Get</h4>
                    <img src="assets/images/pricing-table-01.png" alt="">
                    <p>Check our offers.</p>
                </div>
            </div>
            
            <?php
            // Connect to database
            require 'db_connect.php';
            
            // Get pricing items
            $result = $conn->query("SELECT * FROM pricing_items");
            
            while($item = $result->fetch_assoc()):
                $features = explode("\n", $item['features']);
            ?>
            <div class="col-lg-4">
                <div class="pricing-item-regular">
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                    <div class="icon">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    </div>
                    <ul>
                        <?php foreach($features as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                      <div class="admin-actions mt-3">
    <button class="btn btn-sm btn-primary edit-item" data-id="<?php echo $item['id']; ?>">
        <i class="fas fa-edit mr-1"></i> Edit
    </button>
    <button class="btn btn-sm btn-danger delete-item" data-id="<?php echo $item['id']; ?>">
        <i class="fas fa-trash mr-1"></i> Delete
    </button>
</div>
<?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Pricing Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_title">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_image">Image Path</label>
                        <input type="text" class="form-control" id="edit_image" name="image_path" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_features">Features (one per line)</label>
                        <textarea class="form-control" id="edit_features" name="features" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
<div class="col-12 text-center mb-4">
    <button class="btn btn-success" id="addNewItem">Add New Pricing Item</button>
</div>
<?php endif; ?>
<!-- Add Item Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addModalLabel">Add New Pricing Item</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addForm">
                <input type="hidden" id="add_id" name="id" value="0">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add_title">Title</label>
                        <input type="text" class="form-control" id="add_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="add_image">Image Path</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="add_image" name="image_path" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="browseImageBtn">
                                    <i class="fas fa-folder-open"></i> Browse
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Example: assets/images/pricing-table-01.png</small>
                    </div>
                    <div class="form-group">
                        <label for="add_description">Description</label>
                        <textarea class="form-control" id="add_description" name="description" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="add_features">Features (one per line)</label>
                        <textarea class="form-control" id="add_features" name="features" rows="5" required></textarea>
                        <small class="form-text text-muted">Enter each feature on a new line</small>
                    </div>
                </div>
                <div class="modal-footer">
                  
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
  <footer id="newsletter">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="section-heading">
            <h4>Join our mailing list to receive the news &amp; latest trends</h4>
          </div>
        </div>
        <div class="col-lg-6 offset-lg-3">
         <form id="subscribeForm">
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <fieldset>
                <input type="email" name="email" class="email" placeholder="Email Address..." autocomplete="on" required>
            </fieldset>
        </div>
        <div class="col-lg-6 col-sm-6">
            <fieldset>
                <button type="submit" class="main-button">Subscribe Now <i class="fa fa-angle-right"></i></button>
            </fieldset>
        </div>
    </div>
</form>

        </div>
      </div>
      <div class="row">
        <div class="col-lg-3">
          <div class="footer-widget">
            <h4>Contact Us</h4>
            <p>Rio de Janeiro - RJ, 22795-008, Brazil</p>
            <p><a href="#">010-020-0340</a></p>
            <p><a href="#">info@company.co</a></p>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="footer-widget">
            <h4>About Us</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Testimonials</a></li>
              <li><a href="#">Pricing</a></li>
            </ul>
            <ul>
              <li><a href="#">About</a></li>
              <li><a href="#">Testimonials</a></li>
              <li><a href="#">Pricing</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="footer-widget">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Free Apps</a></li>
              <li><a href="#">App Engine</a></li>
              <li><a href="#">Programming</a></li>
              <li><a href="#">Development</a></li>
              <li><a href="#">App News</a></li>
            </ul>
            <ul>
              <li><a href="#">App Dev Team</a></li>
              <li><a href="#">Digital Web</a></li>
              <li><a href="#">Normal Apps</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="footer-widget">
            <h4>About Our Company</h4>
            <div class="logo">
              <img src="logo.png" alt="">
            </div>
            <p></p>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="copyright-text">
            <p>Copyright © 2025 TunisConnect Company. All Rights Reserved. 
          
          </div>
        </div>
      </div>
    </div>
  </footer>


  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>
</body>
</html>
