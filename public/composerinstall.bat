@echo off
echo Starting Composer Install > "C:\Users\Programming\Herd\SDLabs-Dev\public\combined_output.txt"
echo Command: "C:\Users\Programming\.config\herd\bin\php83\php.exe" "C:\Users\Programming\.config\herd\bin\composer.phar" install >> "C:\Users\Programming\Herd\SDLabs-Dev\public\combined_output.txt"
"C:\Users\Programming\.config\herd\bin\php83\php.exe" "C:\Users\Programming\.config\herd\bin\composer.phar" install >> "C:\Users\Programming\Herd\SDLabs-Dev\public\combined_output.txt" 2>&1
echo Completed Composer Install >> "C:\Users\Programming\Herd\SDLabs-Dev\public\combined_output.txt"