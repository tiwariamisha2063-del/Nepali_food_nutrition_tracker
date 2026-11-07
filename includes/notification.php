
<?php

// Store a notification message
function set_notification($message, $type = "success") {
    $_SESSION["notification"] = [
        "message" => $message,
        "type" => $type
    ];
}

// Display and remove the notification
function display_notification() {

    if (isset($_SESSION["notification"])) {

        $message = $_SESSION["notification"]["message"];
        $type = $_SESSION["notification"]["type"];

        unset($_SESSION["notification"]);
        ?>

        <div class="notification <?php echo htmlspecialchars($type); ?>"
             id="notification">

            <?php echo htmlspecialchars($message); ?>

            <span class="close-btn"
                  onclick="document.getElementById('notification').remove()">
                &times;
            </span>

        </div>

        <script>
            setTimeout(function () {
                const notification =
                    document.getElementById("notification");

                if (notification) {
                    notification.remove();
                }
            }, 4000);
        </script>

        <?php
    }
}

?>
