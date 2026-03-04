
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const filePreviewArea = document.getElementById('filePreviewArea');
            const fileList = document.getElementById('fileList');
            const uploadForm = document.getElementById('uploadForm');
            
            // Handle file selection via Browse button
            fileInput.addEventListener('change', handleFileSelect);
            
            // Handle drag and drop
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('bg-light-hover');
            });
            
            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('bg-light-hover');
            });
            
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('bg-light-hover');
                
                const dt = e.dataTransfer;
                const files = dt.files;
                
                handleFiles(files);
            });
            
            // Click on drop zone to trigger file input
            dropZone.addEventListener('click', function() {
                fileInput.click();
            });
            
            // Handle form submission
            //uploadForm.addEventListener('submit', function(e) {
             //   e.preventDefault();
                
                // Here you would normally send the form data to your server
                // For demonstration, we'll show an alert
              //  alert('Upload functionality would be implemented here. Form data would be sent to the server.');
                
                // Reset form after submission (for demo purposes)
                // uploadForm.reset();
                // fileList.innerHTML = '';
                // filePreviewArea.classList.add('d-none');
            //});
            
            function handleFileSelect(e) {
                const files = e.target.files;
                handleFiles(files);
            }
            
            function handleFiles(files) {
                if (files.length > 0) {
                    filePreviewArea.classList.remove('d-none');
                    fileList.innerHTML = '';
                    
                    Array.from(files).forEach(file => {
                        // Create file preview item
                        const fileItem = document.createElement('div');
                        fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        
                        // Determine file icon based on type
                        let fileIcon = 'fa-file';
                        if (file.type.includes('pdf')) {
                            fileIcon = 'fa-file-pdf text-danger';
                        } else if (file.type.includes('word') || file.name.endsWith('.docx') || file.name.endsWith('.doc')) {
                            fileIcon = 'fa-file-word text-primary';
                        } else if (file.type.includes('spreadsheet') || file.name.endsWith('.xlsx') || file.name.endsWith('.xls')) {
                            fileIcon = 'fa-file-excel text-success';
                        } else if (file.type.includes('presentation') || file.name.endsWith('.pptx') || file.name.endsWith('.ppt')) {
                            fileIcon = 'fa-file-powerpoint text-warning';
                        } else if (file.type.includes('zip') || file.name.endsWith('.zip')) {
                            fileIcon = 'fa-file-archive text-secondary';
                        }
                        
                        // Format file size
                        const fileSize = formatFileSize(file.size);
                        
                        fileItem.innerHTML = `
                            <div class="d-flex align-items-center">
                                <i class="fas ${fileIcon} me-3 fa-lg"></i>
                                <div>
                                    <div>${file.name}</div>
                                    <small class="text-muted">${fileSize}</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        
                        fileList.appendChild(fileItem);
                        
                        // Add remove functionality
                        const removeBtn = fileItem.querySelector('.remove-file');
                        removeBtn.addEventListener('click', function() {
                            fileItem.remove();
                            if (fileList.children.length === 0) {
                                filePreviewArea.classList.add('d-none');
                            }
                        });
                    });
                }
            }
            
            function formatFileSize(bytes) {
                if (bytes < 1024) {
                    return bytes + ' bytes';
                } else if (bytes < 1048576) {
                    return (bytes / 1024).toFixed(1) + ' KB';
                } else {
                    return (bytes / 1048576).toFixed(1) + ' MB';
                }
            }
        });