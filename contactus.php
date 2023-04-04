<?php include './ConnectionString.php';?>
<?php include './header.php';?>
<?php
//https://mdbootstrap.com/docs/b4/jquery/forms/contact/ (For contact form styling)
?>
<!--Section: Contact v.2-->
<section class="mb-4">

    <!--Section heading-->
    <h2 class="h1-responsive font-weight-bold text-center my-4">Contact us</h2>
    <!--Section description-->
    <p class="text-center w-responsive mx-auto mb-5">For any queries or questions pertaining to this site, please leave a message and we will get back to you as soon as possible.</p>

    <div class="row">

        <!--Grid column-->
        <div class="col-md-9 mb-md-0 mb-5">
            <form id="contact-form" name="contact-form" action="mail.php" method="POST">

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <label for="name" class="">Your name</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-6">
                        <div class="md-form mb-0">
                            <label for="email" class="">Your email</label>
                            <input type="text" id="email" name="email" class="form-control">
                            
                        </div>
                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="md-form mb-0">
                            <label for="subject" class="">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control">
                        </div>
                    </div>
                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-12">

                        <div class="md-form">
                            <label for="message">Your message</label>
                            <textarea type="text" id="message" name="message" rows="2" class="form-control md-textarea"></textarea>                            
                        </div>

                    </div>
                </div>
                <!--Grid row-->
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <a class="btn btn-primary-search" onclick="document.getElementById('contact-form').submit();">Send Message</a>
                    </div>
                </div>
            </form>
            <div class="status"></div>
        </div>
        <!--Grid column-->

        <!--Grid column-->
        <div class="col-md-3 text-center">
            <ul class="list-unstyled mb-0">
                <li><i class="fas fa-map-marker-alt fa-2x"></i>
                    <p>Centurion, WierdaPark, South Africa</p>
                </li>

                <li><i class="fas fa-phone mt-4 fa-2x"></i>
                    <p>+27 83 555 5555</p>
                </li>

                <li><i class="fas fa-envelope mt-4 fa-2x"></i>
                    <p>info@bible4all.org.za</p>
                </li>
            </ul>
        </div>
        <!--Grid column-->

    </div>

</section>
<!--Section: Contact v.2-->
<?php          
        include './footer.php';


