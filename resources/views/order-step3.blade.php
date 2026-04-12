<!-- order-step3.blade.php -->
@extends('layouts.content')

@section('content')

<!-- STEPS -->
<section class="bg-white py-6 shadow-sm">
    <div class="max-w-5xl mx-auto flex justify-between items-center text-sm">

        <!-- Step 1 DONE -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">
                <i class="fa-solid fa-check"></i>
            </div>
            <p class="mt-2 font-medium">Your Details</p>
            <p class="text-gray-400 text-xs">Name & Email</p>
        </div>

        <div class="flex-1 h-1 mx-2" style="background-color: #D47497;"></div>

        <!-- Step 2 DONE -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">
                <i class="fa-solid fa-check"></i>
            </div>
            <p class="mt-2 font-medium">What to Print</p>
            <p class="text-gray-400 text-xs">Choose Service</p>
        </div>

        <div class="flex-1 h-1 mx-2" style="background-color: #D47497;"></div>

        <!-- Step 3 ACTIVE -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">3</div>
            <p class="mt-2">Upload Files</p>
        </div>

        <div class="flex-1 h-px bg-gray-300 mx-2"></div>

        <!-- Step 4 -->
        <div class="text-center opacity-40">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mx-auto">4</div>
            <p class="mt-2">Finalize</p>
        </div>

    </div>
</section>

<!-- CONTENT -->
<section class="bg-gradient-to-br from-blue-100 to-gray-200 py-12">

<div class="max-w-4xl mx-auto px-6">

    <h1 class="text-3xl font-bold text-center">Upload Your Design Files</h1>
    <p class="text-center text-gray-500 mt-2">
        Add the files you want us to print
    </p>

    <!-- ✅ FORM START -->
    <form method="POST" action="{{ route('order.step4') }}" enctype="multipart/form-data">
        @csrf

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">

            <!-- HEADER -->
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-upload text-pink-500"></i>
                <h2 class="text-lg font-semibold">Your Files</h2>
            </div>

            <p class="text-sm text-gray-500 mb-6">
                We accept PDF, JPG, PNG, JPEG, and PSD files
            </p>

            <!-- DROP ZONE -->
            <div id="drop-area"
                 class="border-2 border-dashed border-pink-200 rounded-xl p-10 text-center cursor-pointer bg-pink-50">

                <i class="fa-solid fa-arrow-up-from-bracket text-4xl text-blue-400"></i>

                <p class="mt-4 font-medium text-gray-700">
                    Drag & Drop Your Files Here
                </p>
                <p class="text-sm text-gray-500">
                    or click to browse your computer
                </p>

                <!-- ✅ IMPORTANT: name added -->
                <input type="file" id="fileElem" name="files[]" multiple class="hidden">

                <button type="button"
                        class="mt-4 px-4 py-2 border rounded-lg bg-white">
                    Choose Files
                </button>

                <p class="text-xs text-gray-400 mt-4">
                    Supported: PDF, JPG, PNG • Max size: 50MB
                </p>
            </div>

            <!-- FILE LIST -->
            <div class="mt-6">
                <h3 class="font-medium mb-3">Uploaded Files</h3>
                <div id="file-list" class="space-y-3">
                    @if(session('files'))
                        @foreach(session('files') as $file)
                            <div class="flex justify-between items-center bg-gray-100 px-4 py-2 rounded-lg">
                                <span class="text-sm">{{ is_array($file) ? $file['name'] : basename($file) }}</span>
                                <span class="text-xs text-gray-500">Uploaded</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- TIPS -->
            <div class="mt-6 bg-pink-100 border border-pink-300 rounded-xl p-4 text-sm">
                <p class="font-semibold text-pink-600">📄 File Tips</p>
                <ul class="mt-2 space-y-1 text-black-700">
                    <li><i class="fa-solid fa-check fa-lg"></i> Use high quality images (300 DPI or higher)</li>
                    <li><i class="fa-solid fa-check fa-lg"></i> PDF files work best for print</li>
                    <li><i class="fa-solid fa-check fa-lg"></i> Make sure text is readable</li>
                    <li><i class="fa-solid fa-check fa-lg"></i> Add small border around design</li>
                </ul>
            </div>

        </div>

        <!-- BUTTONS -->
        <div class="flex justify-between mt-6">
            <a href="{{ route('order.step2') }}" class="px-5 py-2 border rounded-lg">
                ← Go Back
            </a>

            <!-- ✅ NOW SUBMITS -->
            <button type="submit"
                    class="text-white px-6 py-3 rounded-lg shadow"
                    style="background-color: #D47497;">
                Continue →
            </button>
        </div>

    </form>
    <!-- ✅ FORM END -->

</div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const fileInput = document.getElementById("fileElem");
    const fileList = document.getElementById("file-list");
    const dropArea = document.getElementById("drop-area");
    const button = dropArea.querySelector("button");

    // ✅ FIX: prevent double trigger
    dropArea.addEventListener("click", function (e) {
        // only trigger if NOT clicking button
        if (e.target.tagName !== "BUTTON") {
            fileInput.click();
        }
    });

    fileInput.addEventListener("change", function () {
        displayFiles(this.files);
    });

    function displayFiles(files) {

        Array.from(files).forEach(file => {
            const div = document.createElement("div");
            div.className = "flex justify-between items-center bg-gray-100 px-4 py-2 rounded-lg";

            div.innerHTML = `
                <span class="text-sm">${file.name}</span>
                <span class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            `;

            fileList.appendChild(div); // ✅ append, NOT replace
        });
    }

});
</script>
@endsection