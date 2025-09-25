<?php
// Get current date and time
$current_date = date('l, F j, Y');
$current_time = date('H:i');

// User profile data
$user_name = 'John Doe';
$user_position = 'Software Developer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .priority-low { @apply bg-green-100 text-green-800 border-green-200; }
        .priority-medium { @apply bg-yellow-100 text-yellow-800 border-yellow-200; }
        .priority-high { @apply bg-red-100 text-red-800 border-red-200; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <!-- Profile Section -->
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($user_name); ?></h1>
                    <p class="text-gray-600"><?php echo htmlspecialchars($user_position); ?></p>
                </div>
                
                <!-- Time Section -->
                <div class="text-right">
                    <div class="text-lg md:text-xl font-semibold text-gray-800"><?php echo $current_date; ?></div>
                    <div class="text-gray-600"><?php echo $current_time; ?></div>
                </div>
            </div>
        </div>

        <!-- Add Todo Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Task</h2>
            <form id="todoForm" class="space-y-4">
                <!-- Task Text Area -->
                <div>
                    <label for="task" class="block text-sm font-medium text-gray-700 mb-2">Task Description</label>
                    <textarea 
                        id="task" 
                        name="task" 
                        rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Enter your task here..."
                        required
                    ></textarea>
                </div>
                
                <!-- Priority Level -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                    <select 
                        id="priority" 
                        name="priority" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Add Task
                </button>
            </form>
        </div>

        <!-- Delete All Button -->
        <div id="deleteAllSection" class="mb-6" style="display: none;">
            <button 
                id="deleteAllBtn"
                onclick="deleteAllTodos()"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center"
            >
                <i class="fas fa-trash mr-2"></i>
                Delete All Tasks
            </button>
        </div>

        <!-- Todo Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- To Do Column -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-list-ul mr-2 text-blue-600"></i>
                    To Do
                </h2>
                
                <div id="todoList" class="space-y-3">
                    <div id="emptyTodoState" class="text-center text-gray-500 py-8">
                        <i class="fas fa-check-circle text-4xl mb-4 text-gray-300"></i>
                        <p>No pending tasks!</p>
                    </div>
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                    Done
                </h2>
                
                <div id="doneList" class="space-y-3">
                    <div id="emptyDoneState" class="text-center text-gray-500 py-8">
                        <i class="fas fa-tasks text-4xl mb-4 text-gray-300"></i>
                        <p>No completed tasks yet!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Todo App JavaScript
        let todos = JSON.parse(localStorage.getItem('todos')) || [];
        let nextId = parseInt(localStorage.getItem('nextId')) || 1;

        // Initialize app
        document.addEventListener('DOMContentLoaded', function() {
            loadTodos();
            setupEventListeners();
            updateTime();
            setInterval(updateTime, 60000);
        });

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('todoForm').addEventListener('submit', addTodo);
        }

        // Add new todo
        function addTodo(e) {
            e.preventDefault();
            
            const taskInput = document.getElementById('task');
            const priorityInput = document.getElementById('priority');
            
            if (!taskInput.value.trim()) return;
            
            const todo = {
                id: nextId++,
                task: taskInput.value.trim(),
                priority: priorityInput.value,
                isCompleted: false,
                createdAt: new Date().toISOString(),
                completedAt: null
            };
            
            todos.push(todo);
            saveTodos();
            loadTodos();
            
            // Clear form
            taskInput.value = '';
            priorityInput.value = 'Medium';
        }

        // Toggle todo completion
        function toggleTodo(id) {
            const todo = todos.find(t => t.id === id);
            if (todo) {
                todo.isCompleted = !todo.isCompleted;
                todo.completedAt = todo.isCompleted ? new Date().toISOString() : null;
                saveTodos();
                loadTodos();
            }
        }

        // Delete single todo
        function deleteTodo(id) {
            if (confirm('Are you sure you want to delete this task?')) {
                todos = todos.filter(t => t.id !== id);
                saveTodos();
                loadTodos();
            }
        }

        // Delete all todos
        function deleteAllTodos() {
            if (confirm('Are you sure you want to delete all tasks?')) {
                todos = [];
                saveTodos();
                loadTodos();
            }
        }

        // Save todos to localStorage
        function saveTodos() {
            localStorage.setItem('todos', JSON.stringify(todos));
            localStorage.setItem('nextId', nextId.toString());
        }

        // Load and display todos
        function loadTodos() {
            const todoList = document.getElementById('todoList');
            const doneList = document.getElementById('doneList');
            const deleteAllSection = document.getElementById('deleteAllSection');
            
            const pendingTodos = todos.filter(t => !t.isCompleted);
            const completedTodos = todos.filter(t => t.isCompleted);
            
            // Update delete all button visibility
            deleteAllSection.style.display = todos.length > 0 ? 'block' : 'none';
            
            // Render pending todos
            if (pendingTodos.length === 0) {
                todoList.innerHTML = `
                    <div id="emptyTodoState" class="text-center text-gray-500 py-8">
                        <i class="fas fa-check-circle text-4xl mb-4 text-gray-300"></i>
                        <p>No pending tasks!</p>
                    </div>
                `;
            } else {
                todoList.innerHTML = pendingTodos.map(todo => createTodoHTML(todo)).join('');
            }
            
            // Render completed todos
            if (completedTodos.length === 0) {
                doneList.innerHTML = `
                    <div id="emptyDoneState" class="text-center text-gray-500 py-8">
                        <i class="fas fa-tasks text-4xl mb-4 text-gray-300"></i>
                        <p>No completed tasks yet!</p>
                    </div>
                `;
            } else {
                doneList.innerHTML = completedTodos.map(todo => createTodoHTML(todo, true)).join('');
            }
        }

        // Create todo HTML
        function createTodoHTML(todo, isCompleted = false) {
            const priorityClass = `priority-${todo.priority.toLowerCase()}`;
            const date = new Date(todo.createdAt).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
            
            const completedDate = todo.completedAt ? 
                new Date(todo.completedAt).toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                }) : '';
            
            return `
                <div class="border border-gray-200 rounded-lg p-4 ${isCompleted ? 'bg-gray-50' : 'hover:shadow-sm'} transition duration-200">
                    <div class="flex items-start space-x-3">
                        <!-- Checkbox -->
                        <button onclick="toggleTodo(${todo.id})" class="mt-1 flex-shrink-0">
                            ${isCompleted ? 
                                `<div class="w-5 h-5 bg-green-500 border-2 border-green-500 rounded flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>` :
                                `<div class="w-5 h-5 border-2 border-gray-300 rounded hover:border-blue-500 transition duration-200"></div>`
                            }
                        </button>
                        
                        <!-- Task Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-800 break-words ${isCompleted ? 'line-through text-gray-600' : ''}">${escapeHtml(todo.task)}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${isCompleted ? 'bg-gray-200 text-gray-600' : priorityClass}">
                                    ${todo.priority}
                                </span>
                                <span class="text-xs text-gray-500">
                                    ${isCompleted ? `Completed: ${completedDate}` : date}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Delete Button -->
                        <button onclick="deleteTodo(${todo.id})" class="text-red-500 hover:text-red-700 transition duration-200 flex-shrink-0">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Auto-refresh time every minute
        function updateTime() {
            const now = new Date();
            const timeElements = document.querySelectorAll('.text-gray-600');
            if (timeElements.length > 1) {
                timeElements[1].textContent = now.toLocaleTimeString('en-US', { 
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }
    </script>
</body>
</html>
