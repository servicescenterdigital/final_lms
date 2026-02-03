<?php
// templates/instructor/ai_generator.php
?>
<h3>AI Course Generator</h3>
<p class="text-muted">Follow these steps to generate a course using AI:</p>

<ol>
    <li>Copy the template schema below.</li>
    <li>Paste it into an AI (like ChatGPT) and ask it to fill it with course content.</li>
    <li>Copy the JSON response from the AI and paste it into the "Import JSON" box.</li>
</ol>

<div class="mb-3">
    <label class="form-label">Step 1: Template Schema</label>
    <div class="input-group">
        <textarea id="schema-template" class="form-control" rows="5" readonly></textarea>
        <button class="btn btn-outline-secondary" onclick="copySchema()">Copy</button>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Step 3: Paste AI Generated JSON</label>
    <textarea id="ai-json" class="form-control" rows="10" placeholder='Paste JSON here...'></textarea>
</div>

<button class="btn btn-primary" onclick="importCourse()">Import Course Structure</button>

<div id="import-preview" class="mt-4" style="display:none;">
    <h4>Preview & Validation</h4>
    <div id="preview-content" class="border p-3 bg-light"></div>
    <button class="btn btn-success mt-3" onclick="confirmImport()">Confirm & Publish</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/generate-schema')
        .then(res => res.json())
        .then(data => {
            document.getElementById('schema-template').value = JSON.stringify(data, null, 2);
        });
});

function copySchema() {
    const copyText = document.getElementById("schema-template");
    copyText.select();
    document.execCommand("copy");
    alert("Schema copied to clipboard!");
}

let courseDataToImport = null;

function importCourse() {
    const jsonText = document.getElementById('ai-json').value;
    try {
        courseDataToImport = JSON.parse(jsonText);
        // Basic validation
        if (!courseDataToImport.title || !courseDataToImport.modules) {
            throw new Error("Invalid schema: Title and Modules are required.");
        }
        
        document.getElementById('import-preview').style.display = 'block';
        document.getElementById('preview-content').innerHTML = `
            <h5>${courseDataToImport.title}</h5>
            <p>${courseDataToImport.description}</p>
            <ul>
                ${courseDataToImport.modules.map(m => `<li>${m.title} (${m.lessons ? m.lessons.length : 0} lessons, ${m.quizzes ? m.quizzes.length : 0} quizzes)</li>`).join('')}
            </ul>
        `;
    } catch (e) {
        alert("Error: " + e.message);
    }
}

function confirmImport() {
    if (!courseDataToImport) return;
    
    fetch('/api/import-course', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(courseDataToImport)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Course imported successfully!');
            window.location.href = '/instructor';
        } else {
            alert('Import failed: ' + data.error);
        }
    });
}
</script>
