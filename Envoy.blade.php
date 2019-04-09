@servers(['localhost' => '127.0.0.1'])

@task('deploy')
  git pull origin master
  composer install

  @if ($migrate)
    php artisan migrate:fresh --seed --force
  @endif
@endtask