# Data Aquisition Pipeline

This section of the code is responsible for regularly updating the prices of products in the database.

## Dependencies

- [uv](https://docs.astral.sh/uv/) for managing dependencies, versions and virtual environments
- [geckodriver] is a dependency as well

## Running the Extractor

Navigate to the root directory of the uv project and run
```bash
uv run main.py
```
or if you're using Chrome
```bash
uv run main.py --browser chrome
```

