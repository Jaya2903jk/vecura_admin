@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <h1>Permission Implementation Guide</h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">How to Enforce Permissions in Your Module Pages</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">1. Check Admin Access</h6>
                        <pre><code>&#64;if(session('is_admin'))
    &lt;!-- Admin can see everything --&gt;
&#64;endif</code></pre>

                        <hr>

                        <h6 class="mb-3">2. Hide/Show Buttons Based on Permission</h6>
                        <pre><code>&#64;if(session('is_admin') || canPerformAction('create', 'department'))
    &lt;a href="/department/create" class="btn btn-primary"&gt;
        Add Department
    &lt;/a&gt;
&#64;endif</code></pre>

                        <hr>

                        <h6 class="mb-3">3. Hide/Show Action Buttons Per Row (Recommended)</h6>
                        <pre><code>&#64;forelse($records as $record)
    &lt;tr&gt;
        &lt;td&gt;{{ $record->name }}&lt;/td&gt;
        &lt;td&gt;
            &#64;if(session('is_admin') || canPerformAction('edit', 'module'))
                &lt;a href="..." class="btn btn-warning"&gt;Edit&lt;/a&gt;
            &#64;endif

            &#64;if(session('is_admin') || canPerformAction('delete', 'module'))
                &lt;button class="btn btn-danger"&gt;Delete&lt;/button&gt;
            &#64;endif
        &lt;/td&gt;
    &lt;/tr&gt;
&#64;empty
    &lt;tr&gt;&lt;td&gt;No records found&lt;/td&gt;&lt;/tr&gt;
&#64;endforelse</code></pre>

                        <hr>

                        <h6 class="mb-3">4. Using the Action Buttons Component (Easiest)</h6>
                        <pre><code>&lt;x-action-buttons
    module="department"
    :recordId="$dept->id"
    editRoute="department.edit"
    deleteRoute="department.destroy"
/&gt;</code></pre>

                        <hr>

                        <h6 class="mb-3">5. Restrict Page Access (Entire Index Page)</h6>
                        <pre><code>// In your controller
public function index() {
    if (!session('is_admin') && !canPerformAction('read', 'department')) {
        abort(403, 'Unauthorized');
    }
    return view('department.index');
}</code></pre>

                        <hr>

                        <h6 class="mb-3">6. Get All Allowed Actions for Module</h6>
                        <pre><code>$actions = getAllowedActions('department');
// Returns: ['read', 'edit', 'delete']</code></pre>

                        <hr>

                        <h6 class="mb-3">7. Show Message if No Permissions</h6>
                        <pre><code>&#64;php
    $canRead = session('is_admin') || canPerformAction('read', 'module');
&#64;endphp

&#64;if($canRead)
    &lt;!-- Show table --&gt;
&#64;else
    &lt;div class="alert alert-warning"&gt;
        You don't have permission to view records.
    &lt;/div&gt;
&#64;endif</code></pre>

                        <hr>

                        <h6 class="mb-3">8. Permission List - Available Actions</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Use Case</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-info">read</span></td>
                                        <td>View/list records - hide entire page if user doesn't have this</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">create</span></td>
                                        <td>Create new record - hide "Add" button</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning">edit</span></td>
                                        <td>Edit existing record - hide "Edit" button per row</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">delete</span></td>
                                        <td>Delete record - hide "Delete" button per row</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">approve</span></td>
                                        <td>Approve/submit - hide "Approve" button</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <h6 class="mb-3">9. Quick Implementation Checklist</h6>
                        <div class="alert alert-info">
                            <h6>Step 1: Check Read Permission</h6>
                            <p>Before showing page, verify user has read permission:</p>
                            <pre><code>&#64;if(session('is_admin') || canPerformAction('read', 'module'))
    &lt;!-- Show page content --&gt;
&#64;else
    &lt;div class="alert"&gt;No permission&lt;/div&gt;
&#64;endif</code></pre>

                            <h6 class="mt-3">Step 2: Show Action Buttons</h6>
                            <p>Check each action permission before showing buttons:</p>
                            <pre><code>&#64;if(session('is_admin') || canPerformAction('edit', 'module'))
    &lt;a href="..." class="btn btn-warning"&gt;Edit&lt;/a&gt;
&#64;endif

&#64;if(session('is_admin') || canPerformAction('delete', 'module'))
    &lt;button class="btn btn-danger"&gt;Delete&lt;/button&gt;
&#64;endif</code></pre>

                            <h6 class="mt-3">Step 3: Use Component (Optional)</h6>
                            <p>Or use the component to handle all buttons automatically:</p>
                            <pre><code>&lt;x-action-buttons
    module="your_module"
    :recordId="$record->id"
    editRoute="your_module.edit"
    deleteRoute="your_module.destroy"
/&gt;</code></pre>
                        </div>

                        <hr>

                        <h6 class="mb-3">Helper Methods Reference</h6>
                        <div class="alert alert-secondary">
                            <pre><code>// Check if user can perform action on module
canPerformAction('edit', 'department')
// Returns: true/false

// Get all allowed actions for module
getAllowedActions('department')
// Returns: ['read', 'create', 'edit']

// Check if user is admin (bypasses all permissions)
session('is_admin')
// Returns: true/false</code></pre>
                        </div>

                        <hr>

                        <div class="alert alert-success">
                            <strong>Ready to implement?</strong><br>
                            Choose one of the 3 approaches above and add permission checks to your module pages.
                            Start with page-level read checks, then add button-level checks.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
