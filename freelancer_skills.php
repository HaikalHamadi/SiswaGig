<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Select Skill</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .skills-grid { display:flex; flex-wrap:wrap; gap:10px; margin:15px 0; }
        .skill-chip {
            padding:8px 16px; border-radius:30px; border:1px solid #ccc;
            background:white; cursor:pointer; transition:0.2s; font-size:14px;
        }
        .skill-chip.selected {
            background:linear-gradient(135deg,#ff7b00,#ffaa00);
            color:white; border:none;
        }
        .counter { font-size:14px; color:#666; margin-top:10px; }
        .add-skill-area {
            display:flex; gap:10px; align-items:center;
            margin-top:20px; padding-top:20px;
            border-top:1px dashed #ddd; flex-wrap:wrap;
        }
        .add-skill-area input {
            flex:1; min-width:200px; padding:10px 16px;
            border:1px solid #ddd; border-radius:30px; font-size:14px;
        }
        .btn-add {
            padding:10px 20px; border:2px solid #ff7b00;
            background:transparent; color:#ff7b00;
            border-radius:30px; cursor:pointer; font-weight:bold;
            transition:0.2s; white-space:nowrap;
        }
        .btn-add:hover { background:#ff7b00; color:white; }
        .info-msg { font-size:13px; color:#ff7b00; margin-top:8px; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="card" style="max-width:700px; margin:0 auto;">
        <h2>⭐ Select Your Skills</h2>
        <p>Select at least <strong>3 skills</strong> to get suitable job suggestions.</p>

        <form action="proses_skills.php" method="POST" id="skillsForm">
            <div class="skills-grid" id="skillsGrid"></div>
            <div class="counter">✅ <span id="selectedCount">0</span> / 3 skills are selected</div>
            <input type="hidden" name="skills" id="skillsInput">

            <!-- Tambah kemahiran baru -->
            <div class="add-skill-area">
                <input type="text" id="newSkillInput" placeholder="📝 Type new skills... (eg: Photoshop, SEO)">
                <button type="button" class="btn-add" id="addSkillBtn">+ Add Skills</button>
            </div>
            <div class="info-msg" id="infoMsg"></div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;" onclick="return submitSkills()">Next →</button>
        </form>
    </div>
</div>
<script>
    var defaultSkills = ['HTML','CSS','JavaScript','Java','Python','React','Node.js','Django','Flutter','WordPress','Machine Learning','AWS','Git & GitHub','UI/UX Design','Copywriting','Video Editing'];
    var extraSkills = [];
    var selected = new Set();

    function getAllSkills() {
        return defaultSkills.concat(extraSkills);
    }

    function renderSkillChips() {
        var grid = document.getElementById('skillsGrid');
        grid.innerHTML = '';
        getAllSkills().forEach(function(skill) {
            var chip = document.createElement('div');
            chip.className = 'skill-chip' + (selected.has(skill) ? ' selected' : '');
            chip.innerText = skill;
            chip.onclick = function() {
                if (selected.has(skill)) {
                    selected.delete(skill);
                    chip.classList.remove('selected');
                } else {
                    selected.add(skill);
                    chip.classList.add('selected');
                }
                document.getElementById('selectedCount').innerText = selected.size;
            };
            grid.appendChild(chip);
        });
    }

    document.getElementById('addSkillBtn').onclick = function() {
        var input = document.getElementById('newSkillInput');
        var newSkill = input.value.trim();
        var info = document.getElementById('infoMsg');

        if (!newSkill) {
            info.innerText = '❌ Please enter a skill name.';
            setTimeout(function() { info.innerText = ''; }, 2000);
            return;
        }

        if (getAllSkills().map(function(s){ return s.toLowerCase(); }).includes(newSkill.toLowerCase())) {
            info.innerText = '⚠️ This skill is already on the list.';
            setTimeout(function() { info.innerText = ''; }, 2000);
            input.value = '';
            return;
        }

        extraSkills.push(newSkill);
        selected.add(newSkill); // Auto pilih kemahiran yang baru ditambah
        renderSkillChips();
        document.getElementById('selectedCount').innerText = selected.size;
        info.style.color = '#2e7d32';
        info.innerText = '✅ "' + newSkill + '" successfully added and selected!';
        setTimeout(function() { info.innerText = ''; info.style.color = '#ff7b00'; }, 2000);
        input.value = '';
    };

    // Tekan Enter untuk tambah
    document.getElementById('newSkillInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addSkillBtn').click();
        }
    });

    function submitSkills() {
        if (selected.size < 3) {
            alert('⚠️ Please select at least 3 skills!');
            return false;
        }
        document.getElementById('skillsInput').value = Array.from(selected).join(',');
        return true;
    }

    renderSkillChips();
</script>
</body>
</html>