<?php
/**
 * @file
 * Islandora solr search primary results template file.
 *
 * Variables available:
 * - $results: Primary profile results array
 *
 * @see template_preprocess_islandora_solr()
 */

?>
<?php if (empty($results)): ?>
  <p class="no-results"><?php print t('Sorry, but your search returned no results.'); ?></p>
<?php else: ?>
  <div class="islandora islandora-solr-search-results">
    <?php $row_result = 0; ?>
    <?php foreach($results as $key => $result): ?>
      <!-- islandora_web_annotations related customizations -->
      <?php
      $annotation_parent = $result['solr_doc']["annotation_parent"];
      if ($result['content_models'][0] == "info:fedora/islandora:WADMCModel" && isset($annotation_parent)) {
        $parent_id = $annotation_parent["value"];
        $parent_title = "";
        $parent_tn = drupal_get_path('module', 'islandora_solr') . '/images/defaultimg.png';

        if ($object = islandora_object_load($parent_id)) {
          $parent_title = $object->label;
          $parent_url = "/islandora/object/" . $parent_id;
          $parent_link = "<a href='" . $parent_url . "' title='". $parent_title . "'>" . $parent_title . "</a>";
          $annotation_parent["value"] = $parent_link;

          if ($tn = $object->getDatastream("TN")) {
            $parent_tn = $tn->label;
            $parent_tn = '/islandora/object/' . $parent_id . '/datastream/TN/view';
            $parent_tn_img_link = "<a href='" . $parent_url . "' title='". $parent_title . "'><img typeof='foaf:Image' src='". $parent_tn . "' alt='" . $parent_title . "'></a>";
            $result['thumbnail'] = $parent_tn_img_link;
          }
        }
      }
      ?>

      <!-- Search result -->
      <div class="islandora-solr-search-result clear-block <?php print $row_result % 2 == 0 ? 'odd' : 'even'; ?>">
        <div class="islandora-solr-search-result-inner">
          <!-- Thumbnail -->
          <dl class="solr-thumb">
            <dt>
              <?php print $result['thumbnail']; ?>
            </dt>
            <dd></dd>
          </dl>
          <!-- Metadata -->
          <dl class="solr-fields islandora-inline-metadata">
            <?php foreach($result['solr_doc'] as $key => $value): ?>
              <dt class="solr-label <?php print $value['class']; ?>">
                <?php print $value['label']; ?>
              </dt>
              <dd class="solr-value <?php print $value['class']; ?>">
                <?php print $value['value']; ?>
              </dd>
            <?php endforeach; ?>
          </dl>
        </div>
      </div>
      <?php $row_result++; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
