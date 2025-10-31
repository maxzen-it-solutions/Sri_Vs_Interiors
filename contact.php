<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'header.php';
include 'db_connect.php';

$feedback = '';
$feedback_class = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    $name = strip_tags($name);
    $phone = strip_tags($phone);
    $message = strip_tags($message);

    function has_header_injection($str) {
        return preg_match("/\r|\n|%0A|%0D/i", $str);
    }

    if (!preg_match("/^\d{10}$/", $phone)) {
        $feedback = "Phone number must be exactly 10 digits.";
        $feedback_class = "danger";
    } elseif (has_header_injection($name) || has_header_injection($email) || has_header_injection($phone)) {
        $feedback = "Invalid input detected.";
        $feedback_class = "danger";
    } elseif (empty($name) || empty($email) || empty($phone) || empty($message)) {
        $feedback = "Please fill in all required fields.";
        $feedback_class = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Invalid email address.";
        $feedback_class = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO leads (name, email, phone, message) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $name, $email, $phone, $message);
            $saved = $stmt->execute();

            if ($saved) {
                $feedback = "Thank you " . htmlspecialchars($name) . ", your message has been saved!";
                $feedback_class = "success";
                $name = $email = $phone = $message = ''; // reset
            } else {
                error_log("Database insert error: " . $stmt->error);
                $feedback = "There was an error saving your message.";
                $feedback_class = "danger";
            }
            $stmt->close();
        } else {
            error_log("Prepare statement failed: " . $conn->error);
            $feedback = "Server error. Please try again.";
            $feedback_class = "danger";
        }
    }
}
?>

<!-- Contact Form HTML -->
<section id="subheader">
    <div class="container-fluid m-5-hor">
        <div class="row">
            <div class="col-md-12"><h1>Contact Us</h1></div>
        </div>
    </div>
</section>

<section aria-label="contact" class="whitepage">
    <div class="container-fluid m-5-hor">
        <style>
        .contact-grid { display:flex; gap:20px; align-items:stretch; }
        .contact-left { width:48%; max-width:520px; display:flex; align-items:stretch; }
        .contact-left img { width:100%; height:100%; object-fit:cover; border-radius:8px; }
        .contact-right { flex:1; padding:20px; display:flex; flex-direction:column; gap:16px; }
        @media (max-width: 991px) {
          .contact-grid { flex-direction:column; }
          .contact-left, .contact-right { width:100%; max-width:none; }
        }
        </style>

        <div class="row">
            <div class="col-12">
              <div class="contact-grid">
                <!-- Image on the left -->
                <div class="contact-left">
                  <img src="img/contact-hero.png" alt="Contact Hero Image">
                </div>

                <!-- Contact form on the right -->
                <div class="contact-right">
                  <div class="contact-info" style="background:#fff;padding:16px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.05);">
                    <h3>Contact Information</h3>
                  </div>

                  <div class="contact-form" style="background:#fff;padding:16px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.05);">
                    <?php if (!empty($feedback)): ?>
                        <div class="alert alert-<?php echo $feedback_class; ?>" id="feedback-alert">
                            <?php echo htmlspecialchars($feedback); ?>
                        </div>
                    <?php endif; ?>

                    <form id="form-contact1" method="post" action="contact.php">
                        <div class="form-group user-name">
                            <input 
                                type="text" 
                                class="form-control" 
                                required 
                                name="name" 
                                placeholder="Your Name" 
                                value="<?= isset($name) ? htmlspecialchars($name) : '' ?>"
                                oninvalid="this.setCustomValidity('Please enter your name')"
                                oninput="this.setCustomValidity('')"
                            >
                        </div>

                        <div class="form-group user-email">
                            <input 
                                type="email" 
                                class="form-control" 
                                required 
                                name="email" 
                                placeholder="Your Email" 
                                value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                                oninvalid="this.setCustomValidity('Please enter a valid email address')"
                                oninput="this.setCustomValidity('')"
                            >
                        </div>

                        <div class="form-group user-phone">
                            <input 
                                type="tel" 
                                class="form-control" 
                                required 
                                name="phone" 
                                placeholder="Your Phone Number" 
                                pattern="[0-9]{10}" 
                                maxlength="10" 
                                value="<?= isset($phone) ? htmlspecialchars($phone) : '' ?>"
                                oninvalid="this.setCustomValidity('Please enter a valid 10-digit phone number')"
                                oninput="this.setCustomValidity('')"
                            >
                        </div>

                        <div class="form-group user-message">
                            <textarea 
                                class="form-control" 
                                required 
                                name="message" 
                                placeholder="Your Message"
                                oninvalid="this.setCustomValidity('Please enter your message')"
                                oninput="this.setCustomValidity('')"
                            ><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>
                        </div>

                        <button type="submit" class="btn-contact">Send Now</button>
                    </form>

                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</section>

<script>
setTimeout(() => {
    const alertBox = document.getElementById('feedback-alert');
    if(alertBox) alertBox.style.display = 'none';
}, 5000);
</script>

<?php include 'footer.php'; ?>
