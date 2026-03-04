// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get references to key elements
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const materialsGrid = document.getElementById('materialsGrid');
    const materialsList = document.getElementById('materialsList');
    const noResults = document.getElementById('noResults');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const emptyState = document.getElementById('emptyState');
    const materialsContainer = document.getElementById('materialsContainer');
    
    // Skip JavaScript initialization if materials container doesn't exist
    if (!materialsContainer) {
        return;
    }
    
    // Initialize view based on stored preference or default to grid
    initializeView();
    
    // Set up event listeners for view toggle buttons
    if (gridViewBtn) {
        gridViewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            switchToGridView();
        });
    }
    
    if (listViewBtn) {
        listViewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            switchToListView();
        });
    }
    
    // Filter form handling
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
    
    // Reset filters button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            resetAllFilters();
        });
    }
    
    // Apply initial filters if materials exist
    applyFilters();
    
    // FUNCTIONS
    
    // Initialize view based on saved preference or default to grid
    function initializeView() {
        const savedView = localStorage.getItem('preferredView');
        
        if (savedView === 'list' && materialsList && materialsGrid) {
            switchToListView();
        } else if (materialsGrid && materialsList) {
            // Default to grid view
            switchToGridView();
        }
    }
    
    // Switch to grid view
    function switchToGridView() {
        if (materialsGrid && materialsList) {
            materialsGrid.style.display = 'flex';
            materialsList.style.display = 'none';
            
            // Update active state for buttons
            if (gridViewBtn && listViewBtn) {
                gridViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
            }
            
            // Save preference
            localStorage.setItem('preferredView', 'grid');
            
            // Reapply filters for current view
            applyFilters();
        }
    }
    
    // Switch to list view
    function switchToListView() {
        if (materialsGrid && materialsList) {
            materialsGrid.style.display = 'none';
            materialsList.style.display = 'block';
            
            // Update active state for buttons
            if (gridViewBtn && listViewBtn) {
                gridViewBtn.classList.remove('active');
                listViewBtn.classList.add('active');
            }
            
            // Save preference
            localStorage.setItem('preferredView', 'list');
            
            // Reapply filters for current view
            applyFilters();
        }
    }
    
    // Apply filters to materials
    function applyFilters() {
        // Get current view and items
        const isGridView = materialsList.style.display === 'none';
        const items = isGridView 
            ? materialsGrid.querySelectorAll('.material-item')
            : materialsList.querySelectorAll('.material-item');
            
        // Get filter values
        const titleFilter = document.getElementById('searchTitle')?.value.toLowerCase() || '';
        const courseFilter = document.getElementById('searchCourse')?.value || '';
        const typeFilter = document.getElementById('searchFileType')?.value || '';
        const uploaderFilter = document.getElementById('searchUploader')?.value || '';
        
        let visibleCount = 0;
        
        // Apply filters to each item
        items.forEach(item => {
            // Get item data
            const title = isGridView
                ? item.querySelector('.card-title')?.textContent.toLowerCase() || ''
                : item.querySelector('.list-title')?.textContent.toLowerCase() || '';
                
            const course = item.dataset.course || '';
            const type = item.dataset.type || '';
            const uploader = item.dataset.uploader || '';
            
            // Check if item matches all filters
            const matchesTitle = !titleFilter || title.includes(titleFilter);
            const matchesCourse = !courseFilter || course === courseFilter;
            const matchesType = !typeFilter || type === typeFilter;
            const matchesUploader = !uploaderFilter || uploader === uploaderFilter;
            
            const isVisible = matchesTitle && matchesCourse && matchesType && matchesUploader;
            
            // Show or hide item based on filter results
            if (isVisible) {
                item.style.display = isGridView ? '' : 'table-row';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            // Show the no results message
            if (noResults) noResults.classList.remove('d-none');
            
            // Hide the grid/list views 
            if (isGridView && materialsGrid) {
                materialsGrid.classList.add('d-none');
            } else if (materialsList) {
                materialsList.classList.add('d-none');
            }
        } else {
            // Hide the no results message
            if (noResults) noResults.classList.add('d-none');
            
            // Show the grid/list views
            if (isGridView && materialsGrid) {
                materialsGrid.classList.remove('d-none');
            } else if (materialsList) {
                materialsList.classList.remove('d-none');
            }
        }
        
        // Make sure empty state stays hidden if we have materials
        if (emptyState) {
            emptyState.classList.add('d-none');
        }
    }
    
    // Reset all filters
    function resetAllFilters() {
        // Reset form inputs
        const inputs = {
            'searchTitle': '',
            'searchCourse': 0,
            'searchFileType': 0,
            'searchUploader': 0,
            'searchSort': 0
        };
        
        // Apply reset to form elements
        Object.keys(inputs).forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                if (typeof inputs[id] === 'number') {
                    element.selectedIndex = inputs[id];
                } else {
                    element.value = inputs[id];
                }
            }
        });
        
        // Show all items
        const allItems = document.querySelectorAll('.material-item');
        allItems.forEach(item => {
            if (item.closest('#materialsGrid')) {
                item.style.display = '';
            } else if (item.closest('#materialsList')) {
                item.style.display = 'table-row';
            }
        });
        
        // Reset views visibility
        if (materialsGrid) materialsGrid.classList.remove('d-none');
        if (materialsList) materialsList.classList.remove('d-none');
        
        // Hide no results message
        if (noResults) noResults.classList.add('d-none');
        
        // Make sure we're showing the right view
        const isGridView = localStorage.getItem('preferredView') !== 'list';
        if (isGridView) {
            if (materialsGrid) materialsGrid.style.display = 'flex';
            if (materialsList) materialsList.style.display = 'none';
        } else {
            if (materialsGrid) materialsGrid.style.display = 'none';
            if (materialsList) materialsList.style.display = 'block';
        }
    }
});