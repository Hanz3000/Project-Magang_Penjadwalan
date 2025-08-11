@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Undangan Kolaborasi</h1>
            <p class="text-gray-600">Kelola undangan kolaborasi timeline dari pengguna lain</p>
        </div>

        <!-- Invitations List -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <div id="invites-container">
                <!-- Will be loaded via JavaScript -->
            </div>
        </div>

        <!-- My Collaborated Tasks -->
        <div class="mt-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Timeline yang Saya Kolaborasi
            </h2>
            <div id="collaborated-tasks-container">
                <!-- Will be loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadInvites();
    loadCollaboratedTasks();
});

async function loadInvites() {
    try {
        const response = await fetch('/collaboration/invites');
        const data = await response.json();
        
        const container = document.getElementById('invites-container');
        
        if (data.invites && data.invites.length > 0) {
            container.innerHTML = data.invites.map(invite => `
                <div class="border border-gray-200 rounded-xl p-4 mb-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 mb-2">${invite.task.title}</h3>
                            <p class="text-sm text-gray-600 mb-2">
                                Diundang oleh: <span class="font-medium">${invite.inviter.name}</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                ${invite.can_edit ? '✅ Dengan izin edit' : '👁️ Hanya lihat'}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="respondToInvite(${invite.id}, 'accept')" 
                                class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                                Terima
                            </button>
                            <button onclick="respondToInvite(${invite.id}, 'reject')" 
                                class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition-colors">
                                Tolak
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293L13 15.586a1 1 0 01-.707.293H11a1 1 0 01-.707-.293L8.586 13.293A1 1 0 007.879 13H4"></path>
                    </svg>
                    <p>Tidak ada undangan kolaborasi</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading invites:', error);
    }
}

async function loadCollaboratedTasks() {
    try {
        const response = await fetch('/collaboration/my-tasks');
        const data = await response.json();
        
        const container = document.getElementById('collaborated-tasks-container');
        
        if (data.tasks && data.tasks.length > 0) {
            container.innerHTML = data.tasks.map(task => `
                <div class="border border-gray-200 rounded-xl p-4 mb-4 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 mb-2">${task.title}</h3>
                            <p class="text-sm text-gray-600 mb-2">
                                Owner: <span class="font-medium">${task.user?.name || 'Tidak diketahui'}</span>
                            </p>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span>📅 ${task.start_date_formatted || '-' } → ${task.end_date_formatted || '-' }</span>
                                <span>⏱️ ${task.duration_days ?? 0} hari</span>
                                <span>📊 ${task.calendar_progress ?? 0}% Progress</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="/tasks/${task.id}/edit" 
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>Belum ada timeline yang dikolaborasi</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading collaborated tasks:', error);
    }
}

async function respondToInvite(collaboratorId, action) {
    try {
        const response = await fetch(`/collaboration/respond/${collaboratorId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ action })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            loadInvites();
            loadCollaboratedTasks();
        } else {
            showNotification(data.error || 'Terjadi kesalahan', 'error');
        }
    } catch (error) {
        showNotification('Terjadi kesalahan', 'error');
    }
}

function showNotification(message, type = 'success') {
    // Similar to the notification system in your existing code
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600';
    
    notification.className = `bg-gradient-to-r ${bgColor} text-white px-4 py-3 rounded-lg shadow-xl transform transition-all duration-300 flex items-center gap-2 max-w-xs fixed top-4 right-4 z-50`;
    notification.innerHTML = `
        <span class="font-medium text-sm">${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 4000);
}
async function sendInvite() {
    const email = document.getElementById('collaborator_email').value;
    const canEdit = document.getElementById('can_edit').checked;
    
    try {
        const response = await fetch(`/collaboration/invite/${collaborationState.currentTaskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                email: email,
                can_edit: canEdit
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            closeInviteModal();
            openCollaborationModal(collaborationState.currentTaskId); // Refresh modal
        } else {
            showNotification(data.error || 'Gagal mengirim undangan', 'error');
        }
    } catch (error) {
        showNotification('Terjadi kesalahan', 'error');
        console.error('Error:', error);
    }
}
</script>
@endpush
@endsection