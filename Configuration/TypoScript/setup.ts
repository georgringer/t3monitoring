
plugin.tx_t3monitoring_t3monitor {
  view {
    templateRootPaths.0 = EXT:t3monitoring/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_t3monitoring_t3monitor.view.templateRootPath}
    partialRootPaths.0 = EXT:t3monitoring/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_t3monitoring_t3monitor.view.partialRootPath}
    layoutRootPaths.0 = EXT:t3monitoring/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_t3monitoring_t3monitor.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_t3monitoring_t3monitor.persistence.storagePid}
  }
}

# Module configuration
module.tx_t3monitoring_tools_t3monitoringt3monitor {
  persistence {
    storagePid = {$module.tx_t3monitoring_t3monitor.persistence.storagePid}
  }
  view {
    templateRootPaths.0 = EXT:t3monitoring/Resources/Private/Backend/Templates/
    templateRootPaths.1 = {$module.tx_t3monitoring_t3monitor.view.templateRootPath}
    partialRootPaths.0 = EXT:t3monitoring/Resources/Private/Backend/Partials/
    partialRootPaths.1 = {$module.tx_t3monitoring_t3monitor.view.partialRootPath}
    layoutRootPaths.0 = EXT:t3monitoring/Resources/Private/Backend/Layouts/
    layoutRootPaths.1 = {$module.tx_t3monitoring_t3monitor.view.layoutRootPath}
  }
}
