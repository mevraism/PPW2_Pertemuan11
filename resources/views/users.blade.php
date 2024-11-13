@extends("auth.layouts")
@section("content")
<link rel="stylesheet" href="{{ asset('stylesAdmin.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
    html, body {
        overflow-x: hidden;
        padding: 10px;
    }
    .user-info{
      font-weight:100px;
      font-size: 18px;
    }
    #button{
    background-color: #115C5B;
    color: white;
    }
        #buttonred{
        background-color:#FFE0E0;
        color: #D30000;
    }
        #buttonredreverse{
        background-color:#D30000;
        color: #FFE0E0;
    }
        #buttonedit{
        background-color:#CDFFCD;
        color: #007F00;
    }
    .pagination .page-item.active .page-link {
    background-color: #115C5B;
    border-color: #115C5B;
    color: white;
}
.pagination .page-link {
    color: #115C5B;
}
.pagination .page-link:hover {
    background-color: #115C5B;
    border-color: #115C5B;
    color: white;
}
#logout{
    background-color: white;
    border: 1px solid  #115C5B;
}

#logout:hover{
    background-color:  #115C5B;
    color:white
}
</style>
<div class="row">
<h1 class="h1">Data User</h1>
</div>
<div class="row">
<div class="card mb-4">
<div class="card-header" style="background-color:white !important">
  <!-- Insert Modal -->
  <div class="modal fade" id="insertData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{route("datausers.store")}}" id="uploadForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @csrf
        <!-- Modal Body -->
          <div class="modal-body">
            <div class="mb-3">
                <label for="namaUser">Nama User</label><br>
                <input type="text" name="namaUser" placeholder="Tuliskan nama user" class="form-control"
                  required>
                <div class="invalid-feedback">
                  Input nama user secara valid!
                </div>
                <div class="valid-feedback">
                  Input valid.
                </div>
            </div>

            <div class="mb-3">
                <label for="emailUser">Email User</label><br>
                <input type="text" name="emailUser" placeholder="Tuliskan email user" class="form-control"
                  required>
                <div class="invalid-feedback">
                  Input email user secara valid!
                </div>
                <div class="valid-feedback">
                  Input valid.
                </div>
            </div>

            <div class="mb-3">
                <label for="passwordUser">Password User</label><br>
                <input type="password" name="passwordUser" placeholder="Tuliskan password user" class="form-control"
                  required>
                <div class="invalid-feedback">
                  Input password user secara valid!
                </div>
                <div class="valid-feedback">
                  Input valid.
                </div>
            </div>
            <div class="mb-3">
                <label for="password-confirmation">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" placeholder="Konfirmasi password user" required name="password_confirmation">
            </div>
            <div class="mb-3">
                    <label for="isAdmin" >Role User</label>
                    <div class="col-md-6">
                        <select class="form-select @error('isAdmin') is-invalid @enderror" id="isAdmin" required name="isAdmin">
                            <option value="0">Regular User</option>
                            <option value="1">Admin</option>
                        </select>
                        @if (isset ($error))
                            <div class="alert alert-danger">{{ $errors }}</div>
                        @endif
                    </div>
                </div>

            <label>Foto Profil User</label><br>
            <div class="d-flex flex-column align-items-center">
              <!-- Preview image will appear here -->
                <div id="image-preview" class="border border-gray-400 border-dashed rounded-lg mb-3 p-3"
                    style="width: 200px; height: 200px; display: flex; justify-content: center; align-items: center; cursor: pointer;"
                    onclick="document.getElementById('uploadInput').click();">
                    <p class="text-gray-500">No image selected</p>
                </div>
                <input type="file" name="photo" id="uploadInput" accept="image/*" class="form-control" style="display: none;">
                <button type="button" class="btn btn-danger mt-2" id="clear-button" style="display: none;">
                    Clear Image
                </button>
            </div>
            <div class="invalid-feedback">
              Input foto user secara valid!
            </div>
            <div class="valid-feedback">
              Input valid.
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn" id="button">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Table Section -->
<div class="card-body">
  <!-- Show Alert message when any error occur (especially when error storing data) -->
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li><strong>DATA GAGAL DIUNGGAH</strong></li>
                <li>{{ $error }}</li>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            @endforeach
        </ul>
    </div>
  @endif
  <div class="row">
    <!-- Search Data in Table -->
    <div class="col-md-10">
        <div class="input-group">
          <span class="input-group-text" id="basic-addon1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
          </span>
          <input type="text" id="searchInput" class="form-control" placeholder="Search in this Category..." onkeyup="searchTable()">
        </div>
    </div>
    <div class="col-md-2">
      <!-- Button trigger modal -->
      <button type="button" id="button" class="btn w-100" data-bs-toggle="modal" data-bs-target="#insertData">
        Tambah Data
      </button>
    </div>
  </div>
</div>
<br>

<!-- data user Table -->
<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>NO.</th>
        <th>NAMA USER</th>
        <th>EMAIL</th>
        <th>FOTO PROFIL</th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody id="TableBody">
      <!-- Fill Table Body using Retrieved Data from Database-->
      @foreach($dataUsers as $index => $user)
      <tr>
        <td>{{ $dataUsers->firstItem() + $index }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td class="text-center">
            <img src="{{ asset('storage/' . $user->photo) }}" class="rounded " style="width:250px;height:250px" alt="User Image">
        </td>
        <td>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-xl-start">
                <!-- Edit Button -->
                <button type="button" class="btn" id="buttonedit" data-bs-toggle="modal"
                    data-bs-target="#editModal{{ $user->id }}">
                    Edit
                </button>
                <button type="button" class="btn" id="buttonred" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                    Delete
                </button>
            </div>
        </td>
      </tr>

    <!-- Modal delete -->
        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id}}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Hapus Data User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data user ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('datausers.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" id="buttonredreverse">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

      <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('datausers.update', $user->id) }}" id="editForm" enctype="multipart/form-data" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="namaUser" class="form-label">Nama User</label>
                                <input type="text" class="form-control" name="namaUser" value="{{ $user->name }}">
                            </div>
                            <div class="mb-3">
                                <label for="emailUser" class="form-label">Email User</label>
                                <input type="text" class="form-control" name="emailUser" value="{{ $user->email }}">
                            </div>
                            <div class="mb-3">
                                <label>Foto Profil User</label><br>
                                <div class="d-flex flex-column align-items-center">
                                  <!-- Image Preview will appear here -->
                                    <div id="image-preview-edit-{{ $dataUsers->firstItem() + $index }}" 
                                         class="border border-gray-400 border-dashed rounded-lg mb-3 p-3"
                                         style="width: 200px; height: 200px; display: flex; justify-content: center; align-items: center; cursor: pointer;"
                                         onclick="document.getElementById('editInput-{{ $dataUsers->firstItem() + $index  }}').click();">
                                        <img src="{{ asset('storage/' . $user->photo) }}" 
                                             class="rounded"
                                             style="object-fit: cover; max-width: 100%; max-height: 100%;">
                                    </div>
                                    <input type="file" name="photo" id="editInput-{{$dataUsers->firstItem() + $index }}" accept="image/*" class="form-control" style="display: none;">
                                    <button type="button" class="btn btn-danger mt-2" id="clear-button-edit-{{ $dataUsers->firstItem() + $index  }}">Clear Image</button>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn" id="button">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

      @endforeach
    </tbody>
  </table>
</div>
</div>
</div>

<!-- Pagination links with explicit buttons -->
<div class="d-flex justify-content-center">
<nav aria-label="Page navigation">
<ul class="pagination">
  {{-- Previous Page Link --}}
  @if ($dataUsers->onFirstPage())
    <li class="page-item disabled">
      <span class="page-link">&laquo; Previous</span>
    </li>
  @else
    <li class="page-item">
      <a class="page-link" href="{{ $dataUsers->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
    </li>
  @endif

  {{-- Pagination Elements --}}
  @for ($i = 1; $i <= $dataUsers->lastPage(); $i++)
    <li  class="page-item {{ ($dataUsers->currentPage() == $i) ? 'active' : '' }}">
      <a  class="page-link" href="{{ $dataUsers->url($i) }}">{{ $i }}</a>
    </li>
  @endfor

  {{-- Next Page Link --}}
  @if ($dataUsers->hasMorePages())
    <li class="page-item">
      <a class="page-link" href="{{ $dataUsers->nextPageUrl() }}" rel="next">Next &raquo;</a>
    </li>
  @else
    <li class="page-item disabled">
      <span class="page-link">Next &raquo;</span>
    </li>
  @endif
</ul>
</nav>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>

document.querySelector('form').addEventListener('submit', function(e) {
const password = document.querySelector('input[name="passwordUser"]').value;
const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;

if (password !== confirmPassword) {
    e.preventDefault(); // Mencegah form submit jika password tidak sama
    alert("Password dan konfirmasi password harus sama!");
}else
{
alert("masukk")
}
});


//JS function for image preview in Insert Modal
document.getElementById('uploadInput').addEventListener('change', function(event) {
  const imagePreview = document.getElementById('image-preview');
  const clearButton = document.getElementById('clear-button');
  
  if (event.target.files.length > 0) {
      const file = event.target.files[0];
      const reader = new FileReader();
      
      reader.onload = function(e) {
          imagePreview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded-lg" alt="Image preview" style="max-width: 100%; max-height: 100%;">`;
          clearButton.style.display = 'block';
      };
      
      reader.readAsDataURL(file);
  }
});
document.getElementById('clear-button').addEventListener('click', function() {
  const imagePreview = document.getElementById('image-preview');
  const uploadInput = document.getElementById('uploadInput');
  
  imagePreview.innerHTML = `<p class="text-gray-500">No image selected</p>`;
  uploadInput.value = '';
  this.style.display = 'none';
});


//JS function for image preview in Edit Modal
document.querySelectorAll('[id^="editInput-"]').forEach((input, index) => {
input.addEventListener('change', function(event) {
const imagePreviewEdit = document.getElementById(`image-preview-edit-${index + 1}`);
const clearButtonEdit = document.getElementById(`clear-button-edit-${index + 1}`);

if (event.target.files.length > 0) {
  const file = event.target.files[0];
  const reader = new FileReader();
  
  reader.onload = function(e) {
    imagePreviewEdit.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded-lg" alt="Image preview" style="max-width: 100%; max-height: 100%;">`;
    clearButtonEdit.style.display = 'block';
  };
  
  reader.readAsDataURL(file);
}
});

document.getElementById(`clear-button-edit-${index + 1}`).addEventListener('click', function() {
const imagePreviewEdit = document.getElementById(`image-preview-edit-${index + 1}`);
input.value = '';
this.style.display = 'none';
imagePreviewEdit.innerHTML = `<p class="text-gray-500">No image selected</p>`;
});
});
</script>
@endsection