<div id="automationModal" class="fixed inset-0 bg-black/95 z-50 flex flex-col
           opacity-0 pointer-events-none
           transition-all duration-300 ease-out">

    <!-- HEADER -->
    <div class="flex justify-between items-center p-6 border-b border-gray-800
            transform -translate-y-5 opacity-0 transition-all duration-300" id="automationContent">

        <h2 class="text-xl font-semibold">Automation Panel</h2>

        <div class="flex gap-3">
            <button id="deleteSelectedBtn" class="bg-red-500/20 text-red-400 px-3 py-1 rounded-lg text-sm hidden">
                🗑 Delete Selected
            </button>
            <button id="addAutomationBtn" class="bg-green-500/20 text-green-400 px-3 py-1 rounded-lg text-sm">
                + Add
            </button>

            <button onclick="closeAutomation()" class="text-2xl">✕</button>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="flex-1 overflow-y-auto p-6">
        <div id="automationList" class="grid md:grid-cols-2 gap-4">
            <!-- Data dari AJAX masuk ke sini -->
        </div>
    </div>
</div>

<div id="editAutomationModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center
           opacity-0 pointer-events-none transition-all duration-300">

    <div id="editAutomationContent" class="bg-gray-900 w-full max-w-md rounded-2xl p-6
               transform scale-95 opacity-0 transition-all duration-300">

        <h2 class="text-lg font-semibold mb-4">Edit Automation</h2>

        <input type="hidden" id="edit-id">

        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-400">Time</label>
                <input type="time" id="edit-time" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-400">Topic</label>
                <input type="text" id="edit-topic" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-400">Message</label>
                <input type="text" id="edit-message" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button onclick="closeEditModal()" class="text-gray-400">Cancel</button>
            <button id="saveEditBtn" class="bg-blue-500 px-4 py-2 rounded-lg">Save</button>
        </div>

    </div>
</div>

<div id="addAutomationModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center
           opacity-0 pointer-events-none transition-all duration-300">

    <div id="addAutomationContent" class="bg-gray-900 w-full max-w-md rounded-2xl p-6
               transform scale-95 opacity-0 transition-all duration-300">

        <h2 class="text-lg font-semibold mb-4">Add Automation</h2>

        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-400">Name</label>
                <input type="text" id="add-name" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-400">Time</label>
                <input type="time" id="add-time" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-400">Topic</label>
                <input type="text" id="add-topic" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-400">Message</label>
                <input type="text" id="add-message" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-400">Description</label>
                <input type="text" id="add-description" class="w-full bg-gray-800 rounded-lg px-3 py-2 mt-1">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button onclick="closeAddModal()" class="text-gray-400">Cancel</button>
            <button id="saveAddBtn" class="bg-green-500 px-4 py-2 rounded-lg">Save</button>
        </div>

    </div>
</div>
