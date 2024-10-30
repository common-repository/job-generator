<?php $jobs = HelloworkJobs::param('jobs'); ?>
<?php foreach($jobs as $job): ?>
    <?php HelloworkJobs::param('job', $job); ?>
    <?php HelloworkJobs::template( 'job-detail.html.php'); ?>
<?php endforeach; ?>
