<?php $__env->startSection('title', 'Appointment Calendar'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar3"></i> Appointment Calendar</h2>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-list"></i> List View
        </a>
        <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Appointment
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-calendar-month"></i> <?php echo e($startDate->format('F Y')); ?>

            </h5>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month])); ?>" 
                   class="btn btn-sm btn-light">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
                <a href="<?php echo e(route('calendar.index')); ?>" class="btn btn-sm btn-light">
                    Today
                </a>
                <a href="<?php echo e(route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month])); ?>" 
                   class="btn btn-sm btn-light">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <!-- Day Headers -->
            <div class="calendar-weekdays">
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
            </div>

            <!-- Calendar Days -->
            <div class="calendar-days">
                <?php $__currentLoopData = $calendar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($day === null): ?>
                        <div class="calendar-day empty"></div>
                    <?php else: ?>
                        <div class="calendar-day <?php echo e($day['isToday'] ? 'today' : ''); ?> <?php echo e($day['count'] > 0 ? 'has-appointments' : ''); ?>">
                            <div class="calendar-day-number"><?php echo e($day['date']->format('j')); ?></div>
                            <?php if($day['count'] > 0): ?>
                                <div class="calendar-appointments">
                                    <?php $__currentLoopData = $day['appointments']->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="calendar-appointment" 
                                             onclick="window.location='<?php echo e(route('appointments.show', $appointment)); ?>'"
                                             title="<?php echo e($appointment->patient->first_name); ?> <?php echo e($appointment->patient->last_name); ?> - <?php echo e($appointment->appointment_date->format('g:i A')); ?>">
                                            <small>
                                                <i class="bi bi-clock"></i> <?php echo e($appointment->appointment_date->format('g:i A')); ?>

                                                <br>
                                                <?php echo e(\Illuminate\Support\Str::limit($appointment->patient->first_name . ' ' . $appointment->patient->last_name, 15)); ?>

                                            </small>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($day['count'] > 3): ?>
                                        <div class="calendar-appointment-more">
                                            +<?php echo e($day['count'] - 3); ?> more
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .calendar-grid {
        display: flex;
        flex-direction: column;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #dee2e6;
        border: 1px solid #dee2e6;
    }

    .calendar-day-header {
        background-color: #0d6efd;
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #dee2e6;
        border: 1px solid #dee2e6;
    }

    .calendar-day {
        min-height: 120px;
        background-color: white;
        padding: 8px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.today {
        background-color: #e7f3ff;
        border: 2px solid #0d6efd;
    }

    .calendar-day.has-appointments {
        background-color: #fff3cd;
    }

    .calendar-day.empty {
        background-color: #f8f9fa;
        cursor: default;
    }

    .calendar-day-number {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .calendar-appointments {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .calendar-appointment {
        background-color: #0d6efd;
        color: white;
        padding: 4px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .calendar-appointment:hover {
        opacity: 0.8;
    }

    .calendar-appointment-more {
        background-color: #6c757d;
        color: white;
        padding: 4px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        text-align: center;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
            padding: 4px;
        }

        .calendar-day-number {
            font-size: 0.9rem;
        }

        .calendar-appointment {
            font-size: 0.65rem;
            padding: 2px 4px;
        }

        .calendar-day-header {
            padding: 5px;
            font-size: 0.8rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/calendar/index.blade.php ENDPATH**/ ?>