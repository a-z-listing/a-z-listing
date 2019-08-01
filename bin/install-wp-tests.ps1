Param (
	[string] $DatabaseName,
	[string] $DatabaseUsername,
	[string] $DatabasePassword,
	[string] $DatabaseHostname,
	[Boolean] $SkipCreateDatabase
)

$env:WP_TESTS_DIR = "$PWD/wptests"

Function Install-WordPressTestSuite {
	[CmdletBinding()]
	Param (
		[Parameter(Mandatory=$true, Position=0)]
		[string] $DatabaseName,
		[Parameter(Mandatory=$true, Position=1)]
		[string] $DatabaseUsername,
		[Parameter(Mandatory=$false, Position=2)]
		[string] $DatabasePassword,
		[Parameter(Mandatory=$false, Position=3)]
		[string] $DatabaseHostname
	)

	if (-not $DatabaseHostName) {
		$DatabaseHostName = 'localhost'
	}

	# http serves a single offer, whereas https serves multiple. we only want one
	$json = Invoke-WebRequest -Uri "http://api.wordpress.org/core/version-check/1.7/" | ConvertFrom-JSON
	$LatestWordPressVersion = $json.offers[0].version
	if (-not $LatestWordPressVersion) {
		ThrowError -ExceptionMessage "Latest WordPress version could not be found"
	}
	$WordPressTestTag  = "tags/$LatestWordPressVersion"

	# set up testing suite if it doesn't yet exist
	if (-not (Test-Path $env:WP_TESTS_DIR)) {
		# set up testing suite
		mkdir $env:WP_TESTS_DIR
		svn co --quiet "https://develop.svn.wordpress.org/$WordPressTestTag/tests/phpunit/includes/" "${env:WP_TESTS_DIR}/includes"
		svn co --quiet "https://develop.svn.wordpress.org/$WordPressTestTag/tests/phpunit/data/" "${env:WP_TESTS_DIR}/data"
	}

	if (-not (Test-Path "wp-tests-config.php")) {
		Invoke-WebRequest -Uri "https://develop.svn.wordpress.org/$WordPressTestTag/wp-tests-config-sample.php" -OutFile "${env:WP_TESTS_DIR}/wp-tests-config.php"
		$config = (Get-Content -Path "${env:WP_TESTS_DIR}/wp-tests-config.php" -ReadCount 0) -join "`n"
		$config `
			-replace "dirname\( __FILE__ \) . '/src/'","dirname( __FILE__ ) . '/../../../../'" `
			-replace 'youremptytestdbnamehere',$DatabaseName `
			-replace 'yourusernamehere',$DatabaseUsername `
			-replace 'yourpasswordhere',$DatabasePassword `
			-replace 'localhost',$DatabaseHostName `
			| Set-Content -Path "${env:WP_TESTS_DIR}/wp-tests-config.php"
	}
}

Function Install-WordPressDatabase {
	[CmdletBinding()]
	Param (
		[Parameter(Mandatory=$true, Position=0)]
		[string] $DatabaseName,
		[Parameter(Mandatory=$true, Position=1)]
		[string] $Username,
		[Parameter(Mandatory=$false, Position=2)]
		[string] $Password,
		[Parameter(Mandatory=$false, Position=3)]
		[string] $Hostname,
		[Parameter(Mandatory=$false, Position=4)]
		[Boolean] $SkipCreate
	)

	if ($SkipCreate -eq $true) {
		return
	}

	if (-not $Hostname) {
		$Hostname = 'localhost'
	}

	# create database
	mysqladmin create $DatabaseName --user="$Username" --password="$Password" --host="$Hostname"
}

Install-WordPressTestSuite $DatabaseName $DatabaseUsername $DatabasePassword $DatabaseHostname
Install-WordPressDatabase $DatabaseName $DatabaseUsername $DatabasePassword $DatabaseHostname $SkipCreateDatabase
