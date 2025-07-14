#!/bin/bash

# Base directory for views
VIEWS_DIR="resources/views"

echo "Creating Blade view directory structure and files..."

# 1. Create all necessary directories first
mkdir -p "$VIEWS_DIR"/{auth/{passwords,},components,layouts,admin/{audit,settings,statistics,users},librarian/{authors,books,borrowings,categories,publishers,requests,statistics,tags},student/{books,profile,requests}}

echo "Directories created. Now creating Blade files..."

# 2. Create empty Blade files

# Auth
touch "$VIEWS_DIR"/auth/login.blade.php
touch "$VIEWS_DIR"/auth/passwords/reset.blade.php

# Components
# touch "$VIEWS_DIR"/components/breadcrumb.blade.php
# touch "$VIEWS_DIR"/components/data-table.blade.php
# touch "$VIEWS_DIR"/components/footer.blade.php
# touch "$VIEWS_DIR"/components/modal-form.blade.php
# touch "$VIEWS_DIR"/components/navbar.blade.php
# touch "$VIEWS_DIR"/components/search-form.blade.php
# touch "$VIEWS_DIR"/components/sidebar.blade.php
# touch "$VIEWS_DIR"/components/stat-card.blade.php

# Layouts
touch "$VIEWS_DIR"/layouts/app.blade.php
touch "$VIEWS_DIR"/layouts/auth.blade.php

# Admin
touch "$VIEWS_DIR"/admin/audit/index.blade.php
touch "$VIEWS_DIR"/admin/dashboard.blade.php
touch "$VIEWS_DIR"/admin/settings/index.blade.php
touch "$VIEWS_DIR"/admin/statistics/index.blade.php
touch "$VIEWS_DIR"/admin/users/create.blade.php
touch "$VIEWS_DIR"/admin/users/edit.blade.php
touch "$VIEWS_DIR"/admin/users/index.blade.php
touch "$VIEWS_DIR"/admin/users/show.blade.php

# Librarian
touch "$VIEWS_DIR"/librarian/authors/index.blade.php
touch "$VIEWS_DIR"/librarian/books/create.blade.php
touch "$VIEWS_DIR"/librarian/books/edit.blade.php
touch "$VIEWS_DIR"/librarian/books/index.blade.php
touch "$VIEWS_DIR"/librarian/books/show.blade.php
touch "$VIEWS_DIR"/librarian/borrowings/index.blade.php
touch "$VIEWS_DIR"/librarian/borrowings/show.blade.php
touch "$VIEWS_DIR"/librarian/categories/index.blade.php
touch "$VIEWS_DIR"/librarian/dashboard.blade.php
touch "$VIEWS_DIR"/librarian/publishers/index.blade.php
touch "$VIEWS_DIR"/librarian/requests/index.blade.php
touch "$VIEWS_DIR"/librarian/requests/show.blade.php
touch "$VIEWS_DIR"/librarian/statistics/index.blade.php
touch "$VIEWS_DIR"/librarian/tags/index.blade.php

# Student
touch "$VIEWS_DIR"/student/books/search.blade.php
touch "$VIEWS_DIR"/student/dashboard.blade.php
touch "$VIEWS_DIR"/student/profile/show.blade.php
touch "$VIEWS_DIR"/student/requests/index.blade.php
touch "$VIEWS_DIR"/student/requests/show.blade.php

echo "All Blade files created successfully!"
