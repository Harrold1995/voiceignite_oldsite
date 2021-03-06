<?php
// no direct access
if (! isset($data)) {
	exit;
}

$listAreaStatus = $data['plugin_settings']['assets_list_layout_areas_status'];

/*
* -------------------------------
* [START] BY Loaded or Unloaded
* -------------------------------
*/
if (! empty($data['all']['styles']) || ! empty($data['all']['scripts'])) {
    require_once __DIR__.'/_assets-top-area.php';

    $data['view_by_loaded_unloaded'] =
	$data['rows_build_array'] =
	$data['rows_by_loaded_unloaded'] = true;

	$data['rows_assets'] = array();

	require_once __DIR__.'/_asset-style-rows.php';
	require_once __DIR__.'/_asset-script-rows.php';

	if (! empty($data['rows_assets'])) {
		$handleStatusesText = array(
			'loaded'   => '<span style="color: green;" class="dashicons dashicons-yes"></span>&nbsp; All loaded (.css &amp; .js)',
			'unloaded' => '<span style="color: #cc0000;" class="dashicons dashicons-no-alt"></span>&nbsp; All unloaded (.css &amp; .js)',
		);

		// Sorting: loaded & unloaded
		$rowsAssets = array('loaded' => array(), 'unloaded' => array());

		foreach ($data['rows_assets'] as $handleStatus => $values) {
			$rowsAssets[$handleStatus] = $values;
		}

		foreach ($rowsAssets as $handleStatus => $values) {
			ksort($values);

			$assetRowsOutput = '';

			$totalFiles    = 0;
			$assetRowIndex = 1;

			foreach ($values as $assetType => $assetRows) {
				foreach ($assetRows as $assetRow) {
					$assetRowsOutput .= $assetRow . "\n";
					$totalFiles++;
				}
			}
			?>
            <div class="wpacu-assets-collapsible-wrap wpacu-by-parents wpacu-wrap-area wpacu-<?php echo $handleStatus; ?>">
                <a class="wpacu-assets-collapsible <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-assets-collapsible-active<?php } ?>" href="#wpacu-assets-collapsible-content-<?php echo $handleStatus; ?>">
					<?php echo $handleStatusesText[$handleStatus]; ?> &#10141; Total files: <?php echo $totalFiles; ?>
                </a>

                <div class="wpacu-assets-collapsible-content <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-open<?php } ?>">
					<?php if ($handleStatus === 'loaded') {
                        if (count($values) > 0) {
                            $loadedFilesNote = __('The following files were not selected for unload in any way (e.g. per page, site-wide) on this page. The list also includes any load exceptions (e.g. a file can be unloaded site-wide, but loaded on this page).', 'wp-asset-clean-up');
                        } else {
	                        $loadedFilesNote = __('All the CSS/JS files were chosen to be unloaded on this page', 'wp-asset-clean-up');
                        }
					    ?>
                        <p class="wpacu-assets-note"><?php echo $loadedFilesNote; ?></p>
					<?php } elseif ($handleStatus === 'unloaded') {
					    if (count($values) > 0) {
						    $unloadedFilesNote = __('The following CSS/JS files are unloaded on this page (either only on this page or site-wide)', 'wp-asset-clean-up');
					    } else {
						    $unloadedFilesNote = __('There are no unloaded CSS/JS files on this page', 'wp-asset-clean-up');
                        }
					    ?>
                        <p class="wpacu-assets-note"><?php echo $unloadedFilesNote; ?>.</p>
					<?php } ?>

                    <?php if (count($values) > 0) { ?>
                        <table class="wpacu_list_table wpacu_list_by_parents wpacu_widefat wpacu_striped">
                            <tbody>
                            <?php
                            echo $assetRowsOutput;
                            ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
			<?php
		}
	}
}

if ( isset( $data['all']['hardcoded'] ) && ! empty( $data['all']['hardcoded'] ) ) {
	$data['print_outer_html'] = true; // AJAX call from the Dashboard
	include_once __DIR__ . '/_assets-hardcoded-list.php';
} elseif (isset($hardcodedManageAreaHtml, $data['is_frontend_view']) && $data['is_frontend_view']) {
	echo $hardcodedManageAreaHtml; // AJAX call from the front-end view
}
/*
* ----------------------------
* [END] BY Loaded or Unloaded
* ----------------------------
*/

include_once __DIR__ . '/_page-options.php';

include '_inline_js.php';
