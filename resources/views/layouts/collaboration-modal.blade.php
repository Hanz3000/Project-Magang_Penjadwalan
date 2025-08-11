<!-- Collaboration Modal -->
<div id="collaborationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                <circle cx="6" cy="12" r="2"></circle>
                <circle cx="18" cy="6" r="2"></circle>
                <circle cx="18" cy="18" r="2"></circle>
                <line x1="8" y1="12" x2="16" y2="6" stroke="currentColor" stroke-width="2"></line>
                <line x1="8" y1="12" x2="16" y2="18" stroke="currentColor" stroke-width="2"></line>
            </svg>
                Kelola Kolaborasi
            </h3>
            <button onclick="closeCollaborationModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-white/50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="overflow-y-auto max-h-[calc(80vh-100px)]">
            <div id="collaborationModalContent" class="p-6">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Invite Modal -->
<div id="inviteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        
        <div class="p-6">
            <form id="inviteForm" class="space-y-4">
                <div>
                    <label for="invite_email" class="block text-sm font-medium text-gray-700 mb-1">Email Kolaborator</label>
                    <input type="email" id="invite_email" name="email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="masukkan@email.com">
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="can_edit" name="can_edit" checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="can_edit" class="text-sm text-gray-700">Izinkan mengedit (dengan sistem review)</label>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-2 px-4 rounded-lg font-medium transition-all duration-300">
                        Kirim Undangan
                    </button>
                    <button type="button" onclick="closeInviteModal()"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg font-medium transition-all duration-300">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Revision Review Modal -->
<div id="revisionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-hidden">
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
        
        <div class="overflow-y-auto max-h-[calc(80vh-100px)]">
            <div id="revisionModalContent" class="p-6">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">