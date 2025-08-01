<!-- Collaboration Modal -->
<div id="collaborationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Kelola Kolaborasi
                </h3>
                <button onclick="closeCollaborationModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-white/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="overflow-y-auto max-h-[calc(90vh-100px)]">
                <div id="collaborationModalContent" class="p-4 space-y-4">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invite Modal -->
<div id="inviteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-60">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Undang Kolaborator
                </h3>
                <button onclick="closeInviteModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-white/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-4">
                <form id="inviteForm" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email User</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="user@example.com">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="can_edit" name="can_edit" 
                               class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="can_edit" class="ml-2 text-sm text-gray-700">
                            Izinkan mengedit (perubahan perlu persetujuan)
                        </label>
                    </div>
                    
                    <div class="flex gap-2 pt-2">
                        <button type="submit" 
                                class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Kirim Undangan
                        </button>
                        <button type="button" onclick="closeInviteModal()" 
                                class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Subtask Modal -->
<div id="subtaskModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2" id="subtaskModalTitle">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Subtask
                </h3>
                <button onclick="closeSubtaskModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-white/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-4">
                <form id="subtaskForm" class="space-y-4">
                    <input type="hidden" id="subtask_task_id" name="task_id">
                    <input type="hidden" id="subtask_id" name="subtask_id">
                    
                    <div>
                        <label for="subtask_title" class="block text-sm font-medium text-gray-700 mb-1">Judul Subtask</label>
                        <input type="text" id="subtask_title" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan judul subtask">
                    </div>
                    
                    <div>
                        <label for="subtask_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea id="subtask_description" name="description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Deskripsi subtask"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="subtask_start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" id="subtask_start_date" name="start_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="subtask_end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                            <input type="date" id="subtask_end_date" name="end_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="subtask_start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai (Opsional)</label>
                            <input type="time" id="subtask_start_time" name="start_time"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="subtask_end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai (Opsional)</label>
                            <input type="time" id="subtask_end_time" name="end_time"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-2">
                        <button type="submit" id="subtaskSubmitBtn"
                                class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Simpan Subtask
                        </button>
                        <button type="button" onclick="closeSubtaskModal()" 
                                class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Revision Modal -->
<div id="revisionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Review Usulan Perubahan
                </h3>
                <button onclick="closeRevisionModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-white/50 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="overflow-y-auto max-h-[calc(90vh-100px)]">
                <div id="revisionModalContent" class="p-4">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>