# Rez API Admin + Worker

<b>This is school project for testing and education purposes - not ready to commercial use.</b>

This software allows you to test connection to REST API and use:

- manage places, workers and visits,
- list all system values,
- provide personal listing for each worker

# Setup Docker

- docker-compose build
- docker-compose up -d

# Run API on host by Docker

- visit: http://localhost:9595 (admin)
- visist https://localhost/9595/worker.php (worker area)

# TODO - improvements:

- structures for config based on .env file,
- some cache of settings data,
- better validators and error handling,
- logs aggregation Open Telemetry,
- unit tests for requests (on mocks),
- use DRY instead of copy/paste in methods,