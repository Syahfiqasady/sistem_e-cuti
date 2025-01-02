<?php

$page_name = "Halaman Utama";
include_once('header.php');
include_once('nav.php');

$update = "";
$error = "";
$error = "";
$success = "";

$type = frm("cuti");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = get("id");
    $leave_type_id = $_POST['leave_type_id'];
    $strt_dt = $_POST['strt_dt'];
    $end_dt = $_POST['end_dt'];
    $reason = $_POST['reason'];
    $status = 0;
    $created_dt = date('Y-m-d'); // today's date
    $new_balance = $_POST['hidden_new_balance']; // Get the new balance from the form

    // Calculate days between start and end dates
    $days = (strtotime($end_dt) - strtotime($strt_dt)) / (60 * 60 * 24);

    // Check for file upload (image)
    $img = '';
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $uploadDir = '../uploads/permohonan-cuti/';
        $img = $uploadDir . basename($_FILES["img"]["name"]);

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);  // Create directory if it doesn't exist
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES["img"]["tmp_name"], $img)) {
            $img = $uploadDir . $_FILES["img"]["name"]; // store the path to the file
        } else {
            $error = "Gagal memunggah naik gambar.";
        }
    }

    // Insert into the database if there is no error
    if (empty($error)) {
        $sql = "INSERT INTO leave_request (employee_id, leave_type_id, strt_dt, end_dt, days, img, reason, status, created_dt, balance) 
                VALUES ('$employee_id', '$leave_type_id', '$strt_dt', '$end_dt', '$days', '$img', '$reason', '$status', '$created_dt', '$new_balance')";

        $take_days = mfa(mq("SELECT reserved_days FROM leave_balance WHERE employee_id = $employee_id AND leave_type_id = $leave_type_id"));
        $reserved = $take_days[0] + $days;
        $update_reserved_days = "UPDATE leave_balance SET reserved_days = $reserved WHERE employee_id = $employee_id AND leave_type_id = $leave_type_id";
        mq($update_reserved_days);

        if (mq($sql)) {
            $success = "Permohonan cuti berjaya dihantar!";
        } else {
            $error = "Ralat berlaku. Sila cuba lagi.";
        }
    }
}

// Fetch leave types from database for the dropdown
$leave_types = mq("SELECT id, name FROM leave_type");
// Leave Balance
$type = (!empty($type)) ? $type : 0;
if ($type == 0) {
    // If $type is 0, find the leave_type_id with the lowest ID
    $lowest_type_query = "SELECT leave_type_id FROM leave_balance WHERE employee_id = " . get('id') . " AND year = $current_year ORDER BY leave_type_id ASC LIMIT 1";
    $lowest_type_result = mfa(mq($lowest_type_query));
    $type = $lowest_type_result['leave_type_id'];
}

// Query to fetch leave balance
$query = "SELECT days_balance,reserved_days FROM leave_balance WHERE employee_id = " . get('id') . " AND leave_type_id = $type AND year = $current_year";
$result = mfa(mq($query));
$net_balance = $result[0] - $result[1];

// Query to fetch public holidays from the database, including start and end dates
$publicHolidays = [];
$holidayQuery = mq("SELECT name, date AS start_date, end_date FROM public_holiday");
while ($holiday = mfa($holidayQuery)) {
    $publicHolidays[] = [
        'name' => $holiday['name'] ?? 'Unknown Holiday', // Set default name if NULL
        'start_date' => $holiday['start_date'],
        'end_date' => $holiday['end_date'] ?? $holiday['start_date']  // Use start_date if end_date is NULL
    ];
}

?>


<div class="container leave-dashboard">
    <? include_once('top.php') ?>

    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="leave_type_id" class="form-label">Jenis Cuti</label>
            <select name="leave_type_id" id="leave_type_id" class="form-select" required>
                <?php while ($row = mfa($leave_types)) { ?>
                    <option value="<?= $row['id'] ?>" <?= (!empty($type) && $type == $row['id']) ? 'selected' : '' ?>>
                        <?= $row['name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Baki Cuti Semasa:</label>
            <input type="text" id="current_balance" name="current_balance" class="form-control" value="<?= (!empty($type)) ? $net_balance : 0 ?>" readonly>
        </div>


        <div class="mb-3">
            <label for="strt_dt" class="form-label">Tarikh Mula Cuti</label>
            <input type="date" id="strt_dt" name="strt_dt" class="form-control" onchange="calculateDays()" min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="mb-3">
            <label for="end_dt" class="form-label">Tarikh Kembali Mengajar</label>
            <input type="date" id="end_dt" name="end_dt" class="form-control" onchange="calculateDays()" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Hari Cuti:</label>
            <input type="text" id="days" name="days" class="form-control" readonly>
        </div>


        <div class="mb-3">
            <label>Baki Cuti Baru:</label>
            <input type="text" id="new_balance" name="new_balance" class="form-control" value="" readonly>
            <input type="hidden" id="hidden_new_balance" name="hidden_new_balance">

        </div>


        <div class="mb-3">
            <label for="reason" class="form-label">Sebab</label>
            <textarea id="reason" name="reason" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Lampiran (Gambar)</label>
            <input type="file" id="img" name="img" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Mohon Cuti</button>
    </form>
</div>

<div id="custom-alert" class="modal-overlay">
    <div class="modal-content">
        <h2>Pemberitahuan</h2>
        <p id="custom-alert-message">Tarikh ini adalah Cuti Umum bagi Madrasah</p>
        <button onclick="closeCustomAlert()">OK</button>
    </div>
</div>

<script>
    const publicHolidays = <?php echo json_encode($publicHolidays); ?>;

    document.getElementById("leave_type_id").addEventListener("change", fetchBalance);
    document.getElementById("strt_dt").addEventListener("change", validateAndCalculateDays);
    document.getElementById("end_dt").addEventListener("change", validateAndCalculateDays);

    window.addEventListener('DOMContentLoaded', () => {
        fetchBalance();
        disablePublicHolidays();
    });

    let currentBalance = 0;

    function fetchBalance() {
        const employeeId = <?= get("id") ?>;
        const leaveTypeId = document.getElementById("leave_type_id").value;

        fetch(`ajax.php?employee_id=${employeeId}&leave_type_id=${leaveTypeId}`)
            .then(response => response.json())
            .then(data => {
                currentBalance = data.days_balance;
                document.getElementById("current_balance").value = currentBalance;
                calculateNewBalance();
                updateEndDateMax();
            })
            .catch(error => console.error("Error fetching balance:", error));
    }

    function validateAndCalculateDays() {
        const startDate = new Date(document.getElementById("strt_dt").value);
        const endDate = new Date(document.getElementById("end_dt").value);

        if (isDateRangeInvalid(startDate, endDate)) {
            showCustomAlert("Selected date range includes a public holiday. Please choose another date.");
            document.getElementById("days").value = "";
            return;
        }

        calculateDays();
    }

    function isDateRangeInvalid(start, end) {
        return publicHolidays.some(holiday => {
            const holidayStart = new Date(holiday.start_date);
            const holidayEnd = holiday.end_date ? new Date(holiday.end_date) : holidayStart;

            // Check if the selected range overlaps with any holiday range
            if (start <= holidayEnd && end >= holidayStart) {
                showCustomAlertForHoliday(holidayStart, holidayEnd);
                return true;
            }
            return false;
        });
    }

    function showCustomAlert(message) {
        document.getElementById("custom-alert-message").textContent = message;
        document.getElementById("custom-alert").style.display = "flex";
    }

    function closeCustomAlert() {
        document.getElementById("custom-alert").style.display = "none";
    }

    function calculateDays() {
        const startDate = new Date(document.getElementById("strt_dt").value);
        const endDate = new Date(document.getElementById("end_dt").value);
        endDate.setDate(endDate.getDate() - 1); // Adjust end date to be the last day of leave

        if (isNaN(startDate) || isNaN(endDate) || endDate < startDate) {
            document.getElementById("days").value = "";
            return;
        }

        let dayCount = 0;
        let currentDate = new Date(startDate);

        while (currentDate <= endDate) {
            const dayOfWeek = currentDate.getDay();
            const formattedDate = currentDate.toISOString().split('T')[0];

            if (dayOfWeek !== 0 && dayOfWeek !== 6 && !isDateInHolidayRange(formattedDate)) {
                dayCount++;
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }

        // Check if the requested days exceed the balance
        if (dayCount > currentBalance) {
            showCustomAlert(`Bilangan hari yang dimohon (${dayCount}) melebihi baki cuti yang tersedia (${currentBalance}). Sila pilih tarikh lain. Atau sila ambil CTM untuk hari yang lebih dari yang layak.`);
            document.getElementById("days").value = ""; // Reset the days input
            return;
        }

        document.getElementById("days").value = dayCount;
        calculateNewBalance();
    }

    function isDateInHolidayRange(dateStr) {
        return publicHolidays.some(holiday => {
            const holidayStart = new Date(holiday.start_date);
            const holidayEnd = holiday.end_date ? new Date(holiday.end_date) : holidayStart;

            // Check if the date falls within the holiday range
            const currentDate = new Date(dateStr);
            return currentDate >= holidayStart && currentDate <= holidayEnd;
        });
    }

    function calculateNewBalance() {
        const daysRequested = parseInt(document.getElementById("days").value) || 0;
        const newBalance = currentBalance - daysRequested;
        document.getElementById("new_balance").value = newBalance >= 0 ? newBalance : 0;

        // Set the hidden field for the new balance
        document.getElementById("hidden_new_balance").value = newBalance >= 0 ? newBalance : 0;
    }

    function updateEndDateMax() {
        const startDate = new Date(document.getElementById("strt_dt").value);
        const maxDays = currentBalance;

        let dayCount = 0;
        let maxEndDate = new Date(startDate);

        while (dayCount < maxDays) {
            maxEndDate.setDate(maxEndDate.getDate() + 1);

            const dayOfWeek = maxEndDate.getDay();
            const formattedDate = maxEndDate.toISOString().split('T')[0];

            if (dayOfWeek !== 0 && dayOfWeek !== 6 && !isDateInHolidayRange(formattedDate)) {
                dayCount++;
            }
        }

        document.getElementById("end_dt").setAttribute("max", maxEndDate.toISOString().split('T')[0]);
    }

    function disablePublicHolidays() {
        const dateInputs = [document.getElementById("strt_dt"), document.getElementById("end_dt")];

        dateInputs.forEach(input => {
            input.addEventListener("input", function() {
                const selectedDate = new Date(input.value);

                // Check if the selected date is within any holiday range
                const holiday = publicHolidays.find(holiday => {
                    const holidayStart = new Date(holiday.start_date);
                    const holidayEnd = holiday.end_date ? new Date(holiday.end_date) : holidayStart;
                    return selectedDate >= holidayStart && selectedDate <= holidayEnd;
                });

                // If a holiday is found, show the alert with the holiday's name, start and end dates
                if (holiday) {
                    const holidayStart = new Date(holiday.start_date);
                    const holidayEnd = holiday.end_date ? new Date(holiday.end_date) : holidayStart;

                    // Format the holiday start and end dates as '12 Nov 2024'
                    const dateOptions = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    const startDateStr = holidayStart.toLocaleDateString('en-GB', dateOptions);
                    const endDateStr = holidayEnd.toLocaleDateString('en-GB', dateOptions);
                    // Show the custom alert with the holiday's name, start and end dates
                    const holidayName = holiday.name || "Unknown Holiday"; // Default if name is null or undefined

                    // Show the custom alert with the holiday's name, start and end dates
                    showCustomAlert(`Tarikh ini adalah Cuti Umum bagi Madrasah (${holiday.name}), dari ${startDateStr} sehingga ${endDateStr}. \nSila pilih hari lain.`);
                    input.value = ""; // Clear the input if a holiday is chosen
                }
            });
        });
    }
</script>



<? include_once('footer.php') ?>