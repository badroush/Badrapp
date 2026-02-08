document.addEventListener("DOMContentLoaded", function () {
    // ===== SELECT2 =====
    const typeSelect = document.getElementById('mouvement_stock_type');
    const fournisseurField = document.getElementById('fournisseur-field');
    const beneficiaireField = document.getElementById('beneficiaire-field');
    const fournisseurSelect = document.getElementById('mouvement_stock_fournisseur');
    const beneficiaireSelect = document.getElementById('mouvement_stock_beneficiaire');

    function initSelect2(element, placeholder) {
        $(element).select2({
            theme: "bootstrap-5",
            width: '100%',
            dir: "rtl",
            placeholder: placeholder,
            language: {
                noResults: function () {
                    return "لا توجد نتائج";
                },
                searching: function () {
                    return "جارٍ البحث…";
                },
                inputTooShort: function (args) {
                    return "أدخل " + (args.minimum - args.input.length) + " أحرف إضافية";
                }
            }
        });
    }

    if (fournisseurField) fournisseurField.style.display = 'none';
    if (beneficiaireField) beneficiaireField.style.display = 'none';

    if (typeSelect) {
        typeSelect.addEventListener('change', function () {
            const value = this.value;
            if (fournisseurField) fournisseurField.style.display = 'none';
            if (beneficiaireField) beneficiaireField.style.display = 'none';
            if (fournisseurSelect) fournisseurSelect.value = '';
            if (beneficiaireSelect) beneficiaireSelect.value = '';

            if (value === 'entree' && fournisseurField) {
                fournisseurField.style.display = 'block';
                if (!$(fournisseurSelect).hasClass('select2-hidden-accessible')) {
                    initSelect2(fournisseurSelect, "اختر مورّدًا");
                }
            } else if (value === 'sortie' && beneficiaireField) {
                beneficiaireField.style.display = 'block';
                if (!$(beneficiaireSelect).hasClass('select2-hidden-accessible')) {
                    initSelect2(beneficiaireSelect, "اختر مستفيدًا");
                }
            }
        });

        if (typeSelect.value) {
            typeSelect.dispatchEvent(new Event('change'));
        }
    }

    // ===== DATATABLES =====
    const table = document.getElementById('mouvementsTable');
    if (table) {
        new DataTable('#mouvementsTable', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/ar.json'
            },
            paging: true,
            pageLength: 10,
            lengthChange: false,
            searching: true,
            info: true,
            order: [[0, 'desc']],
            columnDefs: [{
                targets: 2, // colonne "النوع"
                orderDataType: 'dom-text',
                type: 'string'
            }]
        });
    }

    const summaryTable = document.getElementById('stockSummaryTable');
    if (summaryTable) {
        new DataTable('#stockSummaryTable', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/ar.json'
            },
            paging: true,
            pageLength: 10,
            searching: true,
            info: true,
            order: [[0, 'asc']]
        });
    }

    // Initialiser TOUS les champs avec .select2-entity, y compris article
    document.querySelectorAll('.select2-entity').forEach(function (select) {
        if (!$(select).hasClass('select2-hidden-accessible')) {
            $(select).select2({
                theme: "bootstrap-5",
                width: '100%',
                dir: "rtl",
                placeholder: select.getAttribute('data-placeholder') || "ابحث أو اختر...",
                language: {
                    noResults: function () {
                        return "لا توجد نتائج";
                    },
                    searching: function () {
                        return "جارٍ البحث…";
                    }
                }
            });
        }
    });

    // ===== GESTION DES ALERTES =====
    const urlParams = new URLSearchParams(window.location.search);
    const added = urlParams.get('added');

    if (added === 'article') {
        Swal.fire({
            title: 'تم بنجاح!',
            text: 'لقد تمت إضافة المنتج بنجاح.',
            icon: 'success',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#198754',
            iconHtml: '<i class="bi bi-check-circle-fill"></i>',
            customClass: {
                container: 'swal2-rtl'
            }
        }).then(() => {
            history.replaceState(null, null, window.location.pathname);
        });
    }

    if (added === 'mouvement') {
        Swal.fire({
            title: 'تم بنجاح!',
            text: 'لقد تمت إضافة الحركة وتحديث المخزون.',
            icon: 'success',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#198754',
            iconHtml: '<i class="bi bi-check-circle-fill"></i>',
            customClass: {
                container: 'swal2-rtl'
            }
        }).then(() => {
            history.replaceState(null, null, window.location.pathname);
        });
    }

    // ===== SUPPRESSION D'ARTICLE =====
    document.querySelectorAll('.delete-article').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لا يمكن التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                customClass: {
                    container: 'swal2-rtl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/article/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            return response.json().then(err => Promise.reject(err));
                        }
                    }).then(data => {
                        Swal.fire({
                            title: 'تم الحذف!',
                            text: 'لقد تم حذف المنتج بنجاح.',
                            icon: 'success',
                            customClass: {
                                container: 'swal2-rtl'
                            }
                        }).then(() => {
                            location.reload();
                        });
                    }).catch(error => {
                        Swal.fire({
                            title: 'فشل الحذف!',
                            text: ' لا يمكن حذف هذا المنتج لأنه يحتوي على حركات مخزون. يرجى تصفير الرصيد أولاً . لا يمكن حذف المنتج.',
                            icon: 'error',
                            customClass: {
                                container: 'swal2-rtl'
                            }
                        });
                    });
                }
            });
        });
    });

    // ===== SUPPRESSION DE MOUVEMENT =====
    document.querySelectorAll('.delete-mouvement').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم تعديل رصيد المخزون تلقائيًا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذف الحركة!',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                customClass: {
                    container: 'swal2-rtl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/mouvement/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'تم الحذف!',
                                text: 'لقد تم حذف الحركة وتحديث المخزون.',
                                icon: 'success',
                                customClass: {
                                    container: 'swal2-rtl'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    });

    // ===== AJOUT ARTICLE =====
    const articleForm = document.getElementById('articleForm');
    if (articleForm) {
        articleForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(articleForm);
            const action = articleForm.getAttribute('data-action');

            fetch(action, {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    articleForm.reset();
                    $('.select2-entity').val(null).trigger('change');

                    const table = $('#articlesTable').DataTable();
                    const etatHtml = data.article.etat === 'alerte' ? '<span class="badge bg-danger">⚠️ منخفض</span>' : '<span class="badge bg-success">✅ كافٍ</span>';

                    table.row.add([
                        `<span dir="ltr">${data.article.reference}</span>`,
                        data.article.nom,
                        data.article.categorie,
                        data.article.fournisseur,
                        data.article.stock,
                        data.article.seuilAlerte,
                        etatHtml,
                        `<a href="/article/${data.article.id}/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                         <button type="button" class="btn btn-sm btn-outline-danger delete-article" data-id="${data.article.id}"><i class="bi bi-trash"></i></button>`
                    ]).draw(false);

                    Swal.fire({
                        title: 'تم بنجاح!',
                        text: 'لقد تمت إضافة المنتج.',
                        icon: 'success',
                        confirmButtonText: 'حسناً',
                        customClass: {
                            container: 'swal2-rtl'
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'خطأ!',
                        text: data.errors?.join('\n') || 'فشل الإضافة.',
                        icon: 'error',
                        customClass: {
                            container: 'swal2-rtl'
                        }
                    });
                }
            });
        });
    }

    // ===== AJOUT MOUVEMENT =====
    const mouvementForm = document.getElementById('mouvementForm');
    if (mouvementForm) {
        mouvementForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(mouvementForm);
            const action = mouvementForm.getAttribute('data-action');

            fetch(action, {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    mouvementForm.reset();
                    $('#mouvement_stock_type').trigger('change');

                    if ($.fn.DataTable.isDataTable('#mvtTable')) {
                        const table = $('#mvtTable').DataTable();
                        const typeHtml = data.mouvement.type === 'entree' ? '<span class="badge bg-success">📥 دخول</span>' : '<span class="badge bg-danger">📤 خروج</span>';
                        const quantite = data.mouvement.type === 'entree' ? '+' + data.mouvement.quantite : '-' + data.mouvement.quantite;
                        const partie = data.mouvement.fournisseur ? `<i class="bi bi-truck"></i> ${data.mouvement.fournisseur}` : `<i class="bi bi-person"></i> ${data.mouvement.beneficiaire}`;

                        table.row.add([
                            data.mouvement.date,
                            data.mouvement.article,
                            typeHtml,
                            quantite,
                            partie,
                            `<button type="button" class="btn btn-sm btn-outline-danger delete-mouvement" data-id="${data.mouvement.id}"><i class="bi bi-trash"></i></button>`
                        ]).draw(false);
                    }

                    Swal.fire({
                        title: 'تم بنجاح!',
                        text: 'لقد تمت إضافة الحركة.',
                        icon: 'success',
                        customClass: {
                            container: 'swal2-rtl'
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'خطأ!',
                        text: data.message || 'فشل الإضافة.',
                        icon: 'error',
                        customClass: {
                            container: 'swal2-rtl'
                        }
                    });
                }
            });
        });
    }

    // ===== GESTION DES SESSIONS =====
    let inactivityTime = 0;
    const maxInactivityTime = 10 * 60 * 1000; // 10 minutes

    function resetTimer() {
        inactivityTime = 0;
    }

    function checkInactivity() {
        inactivityTime += 1000;

        if (inactivityTime >= maxInactivityTime) {
            Swal.fire({
                title: 'Session expirée',
                text: 'Votre session va expirer. Voulez-vous rester connecté ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Rester connecté',
                cancelButtonText: 'Se déconnecter',
            }).then((result) => {
                if (result.isConfirmed) {
                    resetTimer();
                } else {
                    window.location.href = '/logout';
                }
            });
        }
    }

    document.addEventListener('mousemove', resetTimer);
    document.addEventListener('keypress', resetTimer);
    document.addEventListener('click', resetTimer);

    setInterval(checkInactivity, 1000);

    // ===== CHARGEMENT ASYNCHRONE DU CONTENU =====
    document.querySelectorAll('.sidebar-menu a:not([data-bs-toggle])').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Empêcher le comportement par défaut

            const url = this.getAttribute('href');

            // Afficher un indicateur de chargement
            document.querySelector('.main-content').innerHTML = `
                <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            `;

            // Charger le contenu via AJAX
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Extraire le corps de la réponse HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector('.main-content').innerHTML;

                    // Mettre à jour le contenu
                    document.querySelector('.main-content').innerHTML = newContent;

                    // Réinitialiser les scripts si nécessaire
                    initializeScripts();
                })
                .catch(error => {
                    console.error('Erreur de chargement:', error);
                    document.querySelector('.main-content').innerHTML = `
                        <div class="alert alert-danger">Une erreur est survenue lors du chargement du contenu.</div>
                    `;
                });
        });
    });

    // Fonction pour réinitialiser les scripts après chargement
    function initializeScripts() {
        // Réinitialiser DataTables si présent
        if (typeof DataTable !== 'undefined') {
            document.querySelectorAll('.dataTable').forEach(table => {
                if (table.classList.contains('initialized')) {
                    return; // Éviter les doublons
                }
                table.classList.add('initialized');
                new DataTable(table, {
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/ar.json'
                    }
                });
            });
        }

        // Réinitialiser Select2 si présent
        if (typeof $ !== 'undefined') {
            $('.select2-entity').each(function () {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        theme: "bootstrap-5",
                        width: '100%',
                        dir: "rtl",
                        placeholder: this.getAttribute('data-placeholder') || "ابحث أو اختر...",
                        language: {
                            noResults: function () {
                                return "لا توجد نتائج";
                            },
                            searching: function () {
                                return "جارٍ البحث…";
                            }
                        }
                    });
                }
            });
        }

        // Réinitialiser les dropdowns
        initializeDropdowns();
    }

    // ===== RÉINITIALISER LES DROPDOWNS =====
    function initializeDropdowns() {
        // Réinitialiser les dropdowns Bootstrap
        document.querySelectorAll('body > div:not(.header) [data-bs-toggle="dropdown"], .main-content [data-bs-toggle="dropdown"]').forEach(dropdown => {
            const toggle = dropdown;
            const menu = dropdown.nextElementSibling;

            // Désactiver tout événement existant
            toggle.removeEventListener('click', handleDropdownClick);

            // Réattacher l'événement
            toggle.addEventListener('click', handleDropdownClick);
        });

        function handleDropdownClick(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            const isOpen = menu.classList.contains('show');

            // Fermer tous les autres menus
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                openMenu.classList.remove('show');
            });

            // Ouvrir ou fermer le menu actuel
            if (isOpen) {
                menu.classList.remove('show');
            } else {
                menu.classList.add('show');
            }
        }

        // Fermer les dropdowns en cliquant ailleurs
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    }

    // Appeler la fonction une première fois
    initializeDropdowns();
});