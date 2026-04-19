<!DOCTYPE html>
<html>
<head>
  <title>TaskFlow - Team</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="sidebar">
    <h3><i class="fa-solid fa-folder-open"></i> NextState</h3>
    <br>
    <a href="/" class="nav-item"><i class="fa-solid fa-grip"></i> Dashboard</a>
    <a href="/team" class="nav-item active"><i class="fa-solid fa-users"></i> Team</a>
  </div>

  <div class="content main-content-full">
    <div class="project-header-actions">
        <div>
            <h2>Team Members</h2>
            <p class="text-muted">Manage your team and assignments</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-dark" onclick="document.getElementById('addTeamModal').style.display='flex'"><i class="fa-solid fa-plus"></i> Add Member</button>
        </div>
    </div>

    <div class="team-grid">
        @foreach($teamMembers as $member)
        <div class="team-card border-top-primary">
            <div class="team-card-header">
                <div class="team-avatar"><i class="fa-solid fa-user"></i></div>
                <div class="team-info">
                    <h4>{{ $member->name }}</h4>
                    <span class="role">{{ $member->role }}</span>
                </div>
                <div class="team-actions">
                    <button class="action-btn" title="Edit" onclick="openEditTeamModal({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->phone) }}', '{{ addslashes($member->role) }}')"><i class="fa-solid fa-pencil"></i></button>
                    <button type="button" class="action-btn" title="Delete" onclick="openDeleteModal('{{ route('team.destroy', $member->id) }}', 'menghapus member ini')"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            </div>
            
            <div class="team-contact mt-3">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member->phone) }}" target="_blank" class="contact-link text-muted" title="Contact via WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i> {{ $member->phone }}
                </a>
            </div>

            <div class="team-stats mt-3 pt-3 border-top-light">
                <div class="stat-row">
                    <span>Total Tasks</span>
                    <span class="stat-num">{{ $member->total_tasks }}</span>
                </div>
                <div class="stat-row">
                    <span>Active Tasks</span>
                    <span class="stat-num text-primary">{{ $member->active_tasks }}</span>
                </div>
                <!-- Menambahkan late task sesuai permintaan -->
                <div class="stat-row">
                    <span>Late Tasks</span>
                    <span class="stat-num text-danger">{{ $member->late_tasks }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
  </div>

  <!-- Modal Add Team Member -->
  <div id="addTeamModal" class="modal-overlay" style="display: none;">
      <div class="modal-content">
          <div class="modal-header">
              <h3>Add Team Member</h3>
              <button class="close-btn" onclick="document.getElementById('addTeamModal').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
          </div>
          <p class="text-muted mb-3" style="font-size: 14px;">Add a new member to your team</p>
          <form method="POST" action="{{ route('team.store') }}">
              @csrf
              <div class="form-group">
                  <label>Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Enter member name" required>
              </div>
              <div class="form-group">
                  <label>Phone Number (WhatsApp)</label>
                  <input type="text" name="phone" class="form-control" placeholder="e.g. +6281234..." required>
                  <small class="text-muted">Untuk pemantauan leader, langsung terhubung ke WA</small>
              </div>
              <div class="form-group">
                  <label>Role</label>
                  <input type="text" name="role" class="form-control" placeholder="Enter role (e.g., UI/UX Designer)" required>
              </div>
              <div class="modal-footer mt-4 gap-2" style="display:flex; justify-content:flex-end;">
                  <button type="button" class="btn border-btn" onclick="document.getElementById('addTeamModal').style.display='none'">Cancel</button>
                  <button type="submit" class="btn btn-dark">Add Member</button>
              </div>
          </form>
      </div>
  </div>

  <!-- Modal Edit Team Member -->
  <div id="editTeamModal" class="modal-overlay" style="display: none;">
      <div class="modal-content">
          <div class="modal-header">
              <h3>Edit Team Member</h3>
              <button class="close-btn" onclick="document.getElementById('editTeamModal').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
          </div>
          <p class="text-muted mb-3" style="font-size: 14px;">Update information for this team member</p>
          <form id="editTeamForm" method="POST">
              @csrf
              @method('PUT')
              <div class="form-group mt-3">
                  <label>Name</label>
                  <input type="text" id="edit_team_name" name="name" class="form-control" required>
              </div>
              <div class="form-group mt-3">
                  <label>Phone Number (WhatsApp)</label>
                  <input type="text" id="edit_team_phone" name="phone" class="form-control" required>
              </div>
              <div class="form-group mt-3">
                  <label>Role</label>
                  <input type="text" id="edit_team_role" name="role" class="form-control" required>
              </div>
              <div class="modal-footer mt-4 gap-2" style="display:flex; justify-content:flex-end;">
                  <button type="button" class="btn border-btn" onclick="document.getElementById('editTeamModal').style.display='none'">Cancel</button>
                  <button type="submit" class="btn btn-dark">Update Member</button>
              </div>
          </form>
      </div>
  </div>

  <!-- Modal Delete Confirmation -->
  <div id="deleteConfirmModal" class="modal-overlay" style="display: none; z-index: 2000;">
      <div class="modal-content" style="max-width: 400px; text-align: center;">
          <h3 style="color: #e53e3e; margin-bottom: 15px;"><i class="fa-solid fa-triangle-exclamation"></i> Confirm Deletion</h3>
          <p class="text-muted mb-3" style="font-size: 14px;">Apakah Anda yakin ingin <span id="deleteItemName" style="font-weight:bold;"></span>?</p>
          <form id="deleteModalForm" method="POST">
              @csrf
              @method('DELETE')
              <div class="modal-footer mt-4 gap-2" style="display:flex; justify-content:center;">
                  <button type="button" class="btn border-btn" onclick="document.getElementById('deleteConfirmModal').style.display='none'">Cancel</button>
                  <button type="submit" class="btn btn-danger">Ya, Hapus</button>
              </div>
          </form>
      </div>
  </div>

  <script>
  function openEditTeamModal(id, name, phone, role) {
      document.getElementById('editTeamModal').style.display = 'flex';
      document.getElementById('edit_team_name').value = name;
      document.getElementById('edit_team_phone').value = phone;
      document.getElementById('edit_team_role').value = role;
      document.getElementById('editTeamForm').action = '/team/' + id;
  }

  function openDeleteModal(url, itemName) {
      document.getElementById('deleteConfirmModal').style.display = 'flex';
      document.getElementById('deleteItemName').innerText = itemName;
      document.getElementById('deleteModalForm').action = url;
  }
  </script>

</body>
</html>
