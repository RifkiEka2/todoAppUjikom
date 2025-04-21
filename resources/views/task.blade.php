<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $workspace->title }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 w-3/5 flex flex-col justify-center mx-auto mt-10">
    <div class="shadow-lg w-full rounded-lg h-40 flex flex-col justify-center items-center bg-white">
        <h1 class="font-bold text-3xl">{{ $workspace->title }}</h1>
        <p class="text-sm font-semibold text-gray-500">Dibuat Pada :{{ $workspace->created_at->format('d-m-Y') }}</p>
        <div class="flex gap-2">
            <a href="{{ route('workspace.index') }}">
                <button
                    class="bg-black text-center text-white font-semibold text-sm py-2 px-7 rounded-full mt-4 flex justify-center items-center">Kembali</button>
            </a>
            <button onclick="openModalForm()"
                class="bg-black text-center text-white font-semibold text-sm py-2 px-5 rounded-full mt-4 flex justify-center items-center">Buat
                Tugas</button>
        </div>
    </div>

    <div class="bg-white rounded-md shadow-lg mt-10 py-5 px-7">
        <div class="flex justify-between items-center pb-2 border-b-2">
            <h1 class="font-bold text-xl">List Tugas</h1>
        </div>

        @foreach ($tasks as $task)
            <div class="bg-zinc-800 rounded-md h-32 flex justify-between items-center mt-3 relative hover:bg-zinc-950 ">
                <form action="{{ route('task.updateStatus', $task->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="checkbox" id="task-{{ $task->id }}" name="status" value="completed" class="w-4 h-4 ml-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                        {{ $task->status === 'completed' ? 'checked' : '' }} onclick="this.form.submit()">
                </form>
                <div class="flex flex-col justify-center items-start pl-7 w-full h-full rounded-md">
                    <h1 class="text-white font-bold text-2xl {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                        {{ $task->title }}
                    </h1>
                    <p class="text-white font-semibold text-sm {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                        Tenggat Waktu:{{ $task->deadline->format('d-m-Y') }}
                    </p>
                    @php
                        $priorityColor = match ($task->priority) {
                            'tinggi' => 'bg-red-600',
                            'sedang' => 'bg-yellow-500',
                            'rendah' => 'bg-green-500',
                            default => 'bg-gray-500',
                        };
                    @endphp

                    <div
                        class="{{ $priorityColor }} text-center font-semibold pb-1 px-7 w-fit mt-3 rounded-full text-white {{ $task->status === 'completed' ? 'opacity-50' : '' }}">
                        <p class="capitalize">{{ $task->priority }}</p>
                    </div>
                </div>
                <div class="flex gap-2 absolute right-10">
                    <button onclick="openModalEditTask({{ $task->id }}, '{{ $task->title }}', '{{ $task->deadline }}', '{{ $task->priority }}')" class="bg-white py-2 px-5 rounded-md font-bold">Edit</button>
                    <button onclick="openModalDelete({{ $task->id }})"
                        class="bg-white py-2 px-3 rounded-md font-bold">Hapus</button>
                </div>
            </div>
        @endforeach
    </div>

    <div id="modalForm" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/4">
            <h2 class="text-3xl text-center font-bold mb-4">Buat Tugas</h2>
            <form id="workspaceForm" action="{{ route('task.store') }}" method="POST">
                @csrf
                <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                <input type="hidden" name="status" value="on_progress">

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Judul:</label>
                    <input type="text" id="title" name="title"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        placeholder="Masukan judul Tugas" required>
                </div>

                <!-- Tenggat Waktu -->
                <div class="mb-4">
                    <label for="deadline" class="block text-gray-700 text-sm font-bold mb-2">Tenggat Waktu:</label>
                    <input type="date" id="deadline" name="deadline"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <!-- Prioritas -->
                <div class="mb-4">
                    <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Prioritas:</label>
                    <select id="priority" name="priority"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        required>
                        <option value="" disabled selected>Pilih Prioritas</option>
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeModal"
                        class="bg-zinc-800 text-white py-2 px-4 rounded-md font-bold">Batal</button>
                    <button type="submit" class="bg-zinc-800 text-white py-2 px-3 rounded-md font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalDelete" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/4 flex flex-col items-center justify-center">
            <h2 class="text-2xl text-center font-bold mb-4">Anda Yakin ingin menghapus??</h2>
            <div class="flex justify-end">
            <!-- Delete Form -->
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end">
                    <button type="button" id="closeModalDelete" class="bg-zinc-800 text-white font-bold py-2 px-4 rounded mr-2">Tidak</button>
                    <button type="submit" id="confirmDelete" class="bg-zinc-800 text-white font-bold py-2 px-7 rounded">Ya</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Task -->
<div id="modalEditTask" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/4">
        <h2 class="text-3xl text-center font-bold mb-4">Edit Tugas</h2>
        <form id="editTaskForm" action="" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="editTitle" class="block text-gray-700 text-sm font-bold mb-2">Judul Tugas:</label>
                <input type="text" id="editTitle" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" placeholder="Masukan Judul Tugas" required>
            </div>
            
            <!-- Deadline -->
            <div class="mb-4">
                <label for="editDeadline" class="block text-gray-700 text-sm font-bold mb-2">Deadline:</label>
                <input type="date" id="editDeadline" name="deadline" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
            </div>
            
            <!-- Priority -->
            <div class="mb-4">
                <label for="editPriority" class="block text-gray-700 text-sm font-bold mb-2">Prioritas:</label>
                <select id="editPriority" name="priority" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                    <option value="rendah">Rendah</option>
                    <option value="sedang">Sedang</option>
                    <option value="tinggi">Tinggi</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="closeModalEditTask" class="bg-zinc-800 text-white font-bold py-2 px-4 rounded-md">Batal</button>
                <button type="submit" class="bg-zinc-800 text-white py-2 px-3 rounded-md font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>



    <script>
        const modalForm = document.getElementById('modalForm');
        const closeModal = document.getElementById('closeModal');
        const workspaceForm = document.getElementById('workspaceForm');

        function openModalForm() {
            modalForm.classList.remove('hidden');
        }

        closeModal.addEventListener('click', () => {
            modalForm.classList.add('hidden');
        });


        const modalDelete = document.getElementById('modalDelete');
        const closeModalDelete = document.getElementById('closeModalDelete');
        const deleteForm = document.getElementById('deleteForm');

        function openModalDelete(taskId) {
            deleteForm.action = '/tasks/' + taskId; 
            modalDelete.classList.remove('hidden');
        }

        closeModalDelete.addEventListener('click', () => {
            modalDelete.classList.add('hidden');
        });

        const modalEditTask = document.getElementById('modalEditTask');
        const closeModalEditTask = document.getElementById('closeModalEditTask');
        const editTaskForm = document.getElementById('editTaskForm');
        const editTitle = document.getElementById('editTitle');
        const editDeadline = document.getElementById('editDeadline');
        const editPriority = document.getElementById('editPriority');

        function openModalEditTask(taskId, taskTitle, taskDeadline, taskPriority) {
            editTaskForm.action = '/tasks/' + taskId; 
            editTitle.value = taskTitle; 
            editDeadline.value = taskDeadline; 
            editPriority.value = taskPriority; 
            modalEditTask.classList.remove('hidden');
        }

        closeModalEditTask.addEventListener('click', () => {
            modalEditTask.classList.add('hidden');
        });
    </script>
</body>

</html>
