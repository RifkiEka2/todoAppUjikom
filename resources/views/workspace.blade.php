<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 w-3/5 flex flex-col justify-center mx-auto mt-10">
    <div class="shadow-lg w-full rounded-lg h-40 flex flex-col justify-center items-center bg-white">
        <h1 class="font-bold text-3xl">To-Do List</h1>
        <p class="text-sm font-semibold text-gray-500">Buat harimu jadi lebih produktif.</p>
        <button onclick="openModalForm()" class="bg-black text-center text-white font-semibold text-sm py-2 px-5 rounded-full mt-4 flex justify-center items-center">Buat Ruang Kerja </button>
    </div>

    <div class="bg-white rounded-md shadow-lg mt-10 py-5 px-7">
        <div class="flex justify-between items-center pb-2 border-b-2">
            <h1 class="font-bold text-xl">Daftar Ruang Kerja</h1>
        </div>
        @if($workspaces->isEmpty())
            <p class="text-gray-400 flex justify-center py-20 font-semibold">Anda Tidak Memiliki Ruang Kerja</p>
        @endif
        @foreach ($workspaces as $workspace)  
        <a href="{{ route('task.index', $workspace->id) }}" class="w-full">
            <div class="bg-zinc-800 rounded-md h-28 flex justify-between items-center mt-3 relative hover:bg-zinc-950 cursor-pointer">
                <div class="flex flex-col justify-center items-start pl-10 w-full h-full rounded-md">
                    <h1 class="text-white font-bold text-xl">{{ $workspace->title }}</h1>
                    <p class="text-white font-semibold text-sm">Dibuat Pada : {{ $workspace->created_at->format('d-m-Y') }}</p>
                </div>
                <div class="flex gap-2 absolute right-10">
                    <button onclick="openModalEditWorkspace({{ $workspace->id }}, '{{ $workspace->title }}')" class="bg-white py-2 px-5 rounded-md font-bold">Edit</button>
                    <button onclick="openModalDelete({{ $workspace->id }})" class="bg-white py-2 px-3 rounded-md font-bold" type="button">Hapus</button>
                </div>
            </div>
            </a>          
        @endforeach
    </div>

    <div id="modalForm" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/4">
            <h2 class="text-3xl text-center font-bold mb-4">Buat Ruang Kerja</h2>
            <form id="workspaceForm" action="{{ route('workspace.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Nama Ruang Kerja:</label>
                    <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" placeholder="Masukan Nama Ruang Kerja" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeModal" class="bg-zinc-800 text-white py-2 px-4 rounded-md font-bold">Batal</button>
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
                    <button type="button" id="closeModalDelete" class="bg-zinc-800 text-white hover:bg-zinc-950 font-bold py-2 px-4 rounded mr-2">Tidak</button>
                    <button type="submit" id="confirmDelete" class="bg-zinc-800 text-white hover:bg-zinc-950 font-bold py-2 px-7 rounded">Ya</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Workspace -->
<div id="modalEdit" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/4">
        <h2 class="text-3xl text-center font-bold mb-4">Edit Ruang Kerja</h2>
        <form id="editWorkspaceForm" action="" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="editTitle" class="block text-gray-700 text-sm font-bold mb-2">Nama Ruang Kerja:</label>
                <input type="text" id="editTitle" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" placeholder="Masukan Nama Ruang Kerja" required>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="closeModalEdit" class="bg-zinc-800 text-white font-bold py-2 px-4 rounded-md">Batal</button>
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

        function openModalDelete(workspaceId) {
            event.preventDefault(); 
            deleteForm.action = '/workspaces/' + workspaceId;
            modalDelete.classList.remove('hidden');
        }

        closeModalDelete.addEventListener('click', () => {
            modalDelete.classList.add('hidden');
        });

        const modalEdit = document.getElementById('modalEdit');
        const closeModalEdit = document.getElementById('closeModalEdit');
        const editWorkspaceForm = document.getElementById('editWorkspaceForm');
        const editTitle = document.getElementById('editTitle');

        function openModalEditWorkspace(workspaceId, workspaceTitle) {
            event.preventDefault(); 
            editWorkspaceForm.action = '/workspaces/' + workspaceId;
            editTitle.value = workspaceTitle; 
            modalEdit.classList.remove('hidden');
        }

        closeModalEdit.addEventListener('click', () => {
            modalEdit.classList.add('hidden');
        });


    </script>
</body>
</html>