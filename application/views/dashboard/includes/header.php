<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?= $title ?></title>
        <link rel="shortcut icon" href="<?= $this->config->base_url() ?>images/logo/icon.png">
        <!-- JQUERY -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <!-- Bootstrap core CSS-->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
        <!-- Custom fonts for this template-->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <!-- Page level plugin CSS-->
        <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <!-- Timeline CSS Files-->
        <link rel ="stylesheet" href = "<?= base_url() ?>assets/timeline/timeline.css">
        <!-- Bootstrap Switch -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
        <!-- Bootstrap Lightbox-->
        <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
        <!-- Bootstrap Datepicker -->
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.4.4/css/bootstrap-datetimepicker.min.css">
        <!-- Bootstrap File Upload with preview -->
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/file-upload-with-preview/dist/file-upload-with-preview.min.css">
        <!-- AnimateCss -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" rel="stylesheet">
        <!-- Full Calendar -->
        <link rel ="stylesheet" href ="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.css">
        <!-- SweetAlert -->
        <link rel = "stylesheet" href ="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

        <!-- Custom styles for this template-->
        <link href="<?= base_url() ?>assets/admin/css/sb-admin.css" rel="stylesheet">
        <style>
            button{
                cursor:pointer;
            }
            .custom-file-container__image-clear{
                visibility:hidden;
            }
        </style>
        <script>
            function show_error(form_error, field) {
                if (form_error !== "" || typeof form_error === undefined) {
                    $(field).siblings(".invalid-feedback").remove();
                    $(field).after("<div class = 'invalid-feedback'>" + form_error + "</div>");
                    $(field).removeClass("is-invalid").addClass("is-invalid");
                } else {
                    $(field).siblings(".invalid-feedback").remove();
                    $(field).removeClass("is-invalid");
                }
            }
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                var dogs = "<?php echo $dogs ?>";
                var cats = "<?php echo $cats ?>";
                var adoptable = "<?php echo $adoptable_animals ?>";
                var nonadoptable = "<?php echo $non_adoptable_animals ?>";
                var adopted = "<?php echo $adopted_animals ?>";
                var deceased = "<?php echo $deceased_animals ?>";
                var january = "<?php echo $januaryCount ?>";
                var february = "<?php echo $februaryCount ?>";
                var march = "<?php echo $marchCount ?>";
                var april = "<?php echo $aprilCount ?>";
                var may = "<?php echo $mayCount ?>";
                var june = "<?php echo $juneCount ?>";
                var july = "<?php echo $julyCount ?>";
                var august = "<?php echo $augustCount ?>";
                var september = "<?php echo $septemberCount ?>";
                var october = "<?php echo $octoberCount ?>";
                var november = "<?php echo $novemberCount ?>";
                var december = "<?php echo $decemberCount ?>";
                // Chart.js scripts
                // -- Set new default font family and font color to mimic Bootstrap's default styling
                Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                Chart.defaults.global.defaultFontColor = '#292b2c';
                // -- Pie Chart Example 
                var ctx = document.getElementById("myPieChart");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["Dogs", "Cats"],
                        datasets: [{
                                data: [dogs, cats],
                                backgroundColor: ['#ffc107', '#28a745']
                            }],
                    },
                });
                // -- Pie Chart Example
                var ctx = document.getElementById("myDoughnutChart");
                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ["Adoptable", "Not Adoptable", "Adopted", "Deceased"],
                        datasets: [{
                                data: [adoptable, nonadoptable, adopted, deceased],
                                backgroundColor: ['#2196F3', '#e53935', '#28a745', '#212121']
                            }],
                    },
                });
                // -- Area Chart Example
                var ctx = document.getElementById("myAreaChart");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        datasets: [{
                                label: "Adopted",
                                lineTension: 0.3,
                                backgroundColor: "rgba(2,117,216,0.2)",
                                borderColor: "rgba(2,97,116,1)",
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(2,107,116,1)",
                                pointBorderColor: "rgba(255,255,255,0.8)",
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                                pointHitRadius: 20,
                                pointBorderWidth: 2,
                                data: [january, february, march, april, may, june, july, august, september, october, november, december],
                            }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                    time: {
                                        unit: 'date'
                                    },
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }],
                            yAxes: [{
                                    ticks: {
                                        min: 0,
                                        max: 50,
                                        maxTicksLimit: 5
                                    },
                                    gridLines: {
                                        color: "rgba(0, 0, 0, .125)",
                                    }
                                }],
                        },
                        legend: {
                            display: false
                        }
                    }
                });
            });

        </script>
    </head>
