<?php 
/**
* This is a Class for SSH
*/
class SSH extends CComponent
{
	public function connect($host, $port=22, $method=array(), $callback=array())
	{
		
	}

	/**
	 * Renders a view of shell script
	 * @static
	 * @param string $view name of the view to be rendered. See getViewFile for details about how the view script is resolved.
	 * @param array $data  data to be extracted into PHP variables and made available to the view script
	 * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
	 * @return string the rendering result. Null if the rendering result is not required.
	 */
	public static function renderShellScript($view, $params, $return=True)
	{
		$data = array();

		if (isset($params['args']) && is_array($params['args'])) {
			foreach ($params['args'] as $key => $arg) {
				$data[$key] = escapeshellarg($arg);
			}
			unset($params['args']);
		}
		if (isset($params['cmds']) && is_array($params['cmds'])) {
			foreach($params['cmds'] as $key => $cmd) {
				$data[$key] = escapeshellcmd($cmd);
			}
			unset($params['cmds']);
		}
		return $this->renderPartial($view, $data, $return);
	}
}
?>