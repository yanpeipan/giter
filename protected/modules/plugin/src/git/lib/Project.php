<?php 

/**
 * This is the model extends Projects, and init with id
 */
class Project extends Projects
{
  /**
   * Construct
   * @param integer $id The id of Projects
   */
  function __construct($id)
  {
    $this->pid=$id;
  }

  /**
   * Create a Project
   */
  public function create()
  {

  }

  /**
   * Destroy a Project
   */
  public function destroy()
  {

  }

  /**
   * Publish
   */
  public function publish()
  {

  }
}
