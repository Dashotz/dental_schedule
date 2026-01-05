@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="fade-in-up" style="padding: 2rem;">
    <div style="margin-bottom: 2.5rem;">
        <h2 class="gradient-text" style="font-size: 2rem; font-weight: 700; margin-bottom: 0.75rem; letter-spacing: -0.5px;">
            <i class="mdi mdi-view-dashboard" style="margin-right: 0.75rem; font-size: 2rem;"></i> Dashboard
        </h2>
        <p style="color: rgba(26, 35, 50, 0.7); font-size: 1.1rem;">Welcome back, <strong>{{ auth()->user()->name }}</strong>! Here's what's happening today.</p>
    </div>

    <!-- Statistics Cards - Futuristic Design -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div class="modern-card gradient-card" style="background: linear-gradient(135deg, #00D4FF 0%, #00A8CC 100%); color: white; border: none;">
            <div style="padding: 2rem; position: relative; z-index: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.75rem; font-weight: 500; letter-spacing: 0.5px;">Total Patients</div>
                        <div style="font-size: 3rem; font-weight: 700; line-height: 1; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">{{ $totalPatients }}</div>
                    </div>
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                        <i class="mdi mdi-account-group" style="font-size: 2.5rem; opacity: 0.9;"></i>
                    </div>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.875rem; opacity: 0.8;">
                    <i class="mdi mdi-trending-up" style="margin-right: 0.5rem;"></i> All registered patients
                </div>
            </div>
        </div>
        
        <div class="modern-card gradient-card" style="background: linear-gradient(135deg, #00E676 0%, #00C853 100%); color: white; border: none;">
            <div style="padding: 2rem; position: relative; z-index: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.75rem; font-weight: 500; letter-spacing: 0.5px;">Today's Appointments</div>
                        <div style="font-size: 3rem; font-weight: 700; line-height: 1; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">{{ $todayAppointmentsCount }}</div>
                    </div>
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                        <i class="mdi mdi-calendar-check" style="font-size: 2.5rem; opacity: 0.9;"></i>
                    </div>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.875rem; opacity: 0.8;">
                    <i class="mdi mdi-clock-outline" style="margin-right: 0.5rem;"></i> Scheduled for today
                </div>
            </div>
        </div>
        
        <div class="modern-card gradient-card" style="background: linear-gradient(135deg, #00B8D4 0%, #0097A7 100%); color: white; border: none;">
            <div style="padding: 2rem; position: relative; z-index: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.75rem; font-weight: 500; letter-spacing: 0.5px;">Pending Appointments</div>
                        <div style="font-size: 3rem; font-weight: 700; line-height: 1; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">{{ $pendingAppointments }}</div>
                    </div>
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                        <i class="mdi mdi-clock-outline" style="font-size: 2.5rem; opacity: 0.9;"></i>
                    </div>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.875rem; opacity: 0.8;">
                    <i class="mdi mdi-alert-circle" style="margin-right: 0.5rem;"></i> Awaiting confirmation
                </div>
            </div>
        </div>
        
        <div class="modern-card gradient-card" style="background: linear-gradient(135deg, #FF6F00 0%, #E65100 100%); color: white; border: none;">
            <div style="padding: 2rem; position: relative; z-index: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.75rem; font-weight: 500; letter-spacing: 0.5px;">Total Appointments</div>
                        <div style="font-size: 3rem; font-weight: 700; line-height: 1; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">{{ $totalAppointments }}</div>
                    </div>
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                        <i class="mdi mdi-calendar" style="font-size: 2.5rem; opacity: 0.9;"></i>
                    </div>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.875rem; opacity: 0.8;">
                    <i class="mdi mdi-chart-line" style="margin-right: 0.5rem;"></i> All time total
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Today's Appointments -->
        <div class="modern-card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
            <div style="background: linear-gradient(135deg, #00D4FF 0%, #00A8CC 100%); color: white; padding: 1.5rem; border-radius: 20px 20px 0 0;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <i class="mdi mdi-calendar-today" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Today's Appointments</h3>
                </div>
            </div>
            <div style="padding: 1.5rem;">
                @if($todayAppointments->count() > 0)
                    <div>
                        @foreach($todayAppointments as $appointment)
                            <a href="{{ route('appointments.show', $appointment) }}" 
                               class="modern-card" 
                               style="display: block; padding: 1.25rem; text-decoration: none; color: inherit; margin-bottom: 1rem; background: rgba(0, 212, 255, 0.05); border: 1px solid rgba(0, 212, 255, 0.1); border-radius: 16px; transition: all 0.3s;"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0, 212, 255, 0.15)'; this.style.borderColor='rgba(0, 212, 255, 0.3)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='rgba(0, 212, 255, 0.1)';">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                    <strong style="font-size: 1.1rem; color: #1A2332;">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong>
                                    <span style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: {{ $appointment->status === 'completed' ? 'linear-gradient(135deg, #00E676, #00C853)' : ($appointment->status === 'cancelled' ? 'linear-gradient(135deg, #FF5252, #D32F2F)' : 'linear-gradient(135deg, #00D4FF, #00A8CC)') }}; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <div style="color: rgba(26, 35, 50, 0.7); font-size: 0.95rem; display: flex; align-items: center;">
                                    <i class="mdi mdi-clock-outline" style="margin-right: 0.5rem; color: #00A8CC;"></i>
                                    <span style="font-weight: 500;">{{ $appointment->appointment_date->format('g:i A') }}</span>
                                    <span style="margin: 0 0.5rem;">•</span>
                                    <span>{{ ucfirst($appointment->type) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem 2rem;">
                        <i class="mdi mdi-calendar-remove" style="font-size: 3rem; color: rgba(0, 212, 255, 0.3); margin-bottom: 1rem;"></i>
                        <p style="color: rgba(26, 35, 50, 0.6); font-size: 1rem;">No appointments scheduled for today.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="modern-card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
            <div style="background: linear-gradient(135deg, #00E676 0%, #00C853 100%); color: white; padding: 1.5rem; border-radius: 20px 20px 0 0;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <i class="mdi mdi-calendar-range" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Upcoming Appointments</h3>
                </div>
            </div>
            <div style="padding: 1.5rem;">
                @if($upcomingAppointments->count() > 0)
                    <div>
                        @foreach($upcomingAppointments as $appointment)
                            <a href="{{ route('appointments.show', $appointment) }}" 
                               class="modern-card" 
                               style="display: block; padding: 1.25rem; text-decoration: none; color: inherit; margin-bottom: 1rem; background: rgba(0, 230, 118, 0.05); border: 1px solid rgba(0, 230, 118, 0.1); border-radius: 16px; transition: all 0.3s;"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0, 230, 118, 0.15)'; this.style.borderColor='rgba(0, 230, 118, 0.3)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='rgba(0, 230, 118, 0.1)';">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                    <strong style="font-size: 1.1rem; color: #1A2332;">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong>
                                    <span style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 168, 204, 0.15)); color: #00A8CC; border: 1px solid rgba(0, 168, 204, 0.3);">
                                        {{ $appointment->appointment_date->format('M d') }}
                                    </span>
                                </div>
                                <div style="color: rgba(26, 35, 50, 0.7); font-size: 0.95rem; display: flex; align-items: center;">
                                    <i class="mdi mdi-clock-outline" style="margin-right: 0.5rem; color: #00C853;"></i>
                                    <span style="font-weight: 500;">{{ $appointment->appointment_date->format('g:i A') }}</span>
                                    <span style="margin: 0 0.5rem;">•</span>
                                    <span>{{ ucfirst($appointment->type) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem 2rem;">
                        <i class="mdi mdi-calendar-clock" style="font-size: 3rem; color: rgba(0, 230, 118, 0.3); margin-bottom: 1rem;"></i>
                        <p style="color: rgba(26, 35, 50, 0.6); font-size: 1rem;">No upcoming appointments.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="modern-card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
        <div style="padding: 1.5rem; border-bottom: 1px solid rgba(0, 212, 255, 0.1);">
            <div style="display: flex; align-items: center;">
                <div style="width: 45px; height: 45px; background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 168, 204, 0.15)); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                    <i class="mdi mdi-account-group" style="font-size: 1.5rem; color: #00A8CC;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0; color: #1A2332;">Recent Patients</h3>
            </div>
        </div>
        <div style="padding: 0;">
            @if($recentPatients->count() > 0)
                <div class="modern-table" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, rgba(0, 212, 255, 0.08), rgba(0, 168, 204, 0.04));">
                                <th style="padding: 1.25rem; text-align: left; font-weight: 600; color: #1A2332; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid rgba(0, 212, 255, 0.2);">Name</th>
                                <th style="padding: 1.25rem; text-align: left; font-weight: 600; color: #1A2332; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid rgba(0, 212, 255, 0.2);">Phone</th>
                                <th style="padding: 1.25rem; text-align: left; font-weight: 600; color: #1A2332; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid rgba(0, 212, 255, 0.2);">Email</th>
                                <th style="padding: 1.25rem; text-align: left; font-weight: 600; color: #1A2332; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid rgba(0, 212, 255, 0.2);">Registered</th>
                                <th style="padding: 1.25rem; text-align: left; font-weight: 600; color: #1A2332; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid rgba(0, 212, 255, 0.2);">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPatients as $patient)
                                <tr style="border-bottom: 1px solid rgba(0, 212, 255, 0.1); transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='rgba(0, 212, 255, 0.05)'; this.style.transform='scale(1.01)';"
                                    onmouseout="this.style.backgroundColor='transparent'; this.style.transform='scale(1)';">
                                    <td style="padding: 1.25rem; font-weight: 500; color: #1A2332;">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                    <td style="padding: 1.25rem; color: rgba(26, 35, 50, 0.7);">{{ $patient->phone }}</td>
                                    <td style="padding: 1.25rem; color: rgba(26, 35, 50, 0.7);">{{ $patient->email ?? 'N/A' }}</td>
                                    <td style="padding: 1.25rem; color: rgba(26, 35, 50, 0.7); font-size: 0.9rem;">{{ $patient->created_at->diffForHumans() }}</td>
                                    <td style="padding: 1.25rem;">
                                        <a href="{{ route('patients.show', $patient) }}" 
                                           class="modern-btn" 
                                           style="display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #00D4FF, #00A8CC); color: white; text-decoration: none; border-radius: 12px; font-size: 0.875rem; font-weight: 500; transition: all 0.3s; box-shadow: 0 4px 12px rgba(0, 212, 255, 0.3);"
                                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 212, 255, 0.4)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 212, 255, 0.3)';">
                                            <i class="mdi mdi-eye" style="margin-right: 0.5rem; font-size: 1rem;"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 3rem 2rem;">
                    <i class="mdi mdi-account-off" style="font-size: 3rem; color: rgba(0, 212, 255, 0.3); margin-bottom: 1rem;"></i>
                    <p style="color: rgba(26, 35, 50, 0.6); font-size: 1rem;">No patients registered yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
