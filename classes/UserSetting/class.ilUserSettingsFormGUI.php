<?php
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Customizing/global/plugins/Services/EventHandling/EventHook/UserDefaults/classes/Form/class.ilContainerMultiSelectInputGUI.php');
require_once("./Customizing/global/plugins/Services/EventHandling/EventHook/UserDefaults/classes/Form/class.udfMultiLineInputGUI.php");
require_once("./Services/Form/classes/class.ilRepositorySelectorInputGUI.php");

/**
 * Class ilUserSettingsFormGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilUserSettingsFormGUI extends ilPropertyFormGUI {

	const F_TITLE = 'title';
	const F_STATUS = 'status';
	const F_GLOBAL_ROLE = 'global_role';
	const F_ASSIGNED_COURSES = 'assigned_courses';
	const F_ASSIGNED_COURSES_DESKTOP = 'assigned_courses_desktop';
	const F_ASSIGNED_GROUPS = 'assigned_groups';
	const F_ASSIGNED_GROUPS_DESKTOP = 'assigned_groups_desktop';
	const F_PORTFOLIO_TEMPLATE_ID = 'portfolio_template_id';
	const F_PORTFOLIO_ASSIGNED_TO_GROUPS = 'portfolio_assigned_to_groups';
	const F_ASSIGNED_ORGUS = 'assigned_orgus';
	const F_ASSIGNED_STUDYPROGRAMS = 'assigned_studyprograms';
	const F_DESCRIPTION = 'description';
	const F_PORTFOLIO_NAME = 'portfolio_name';
	const F_BLOG_NAME = 'blog_name';
	/**
	 * @var ilUserSettingsGUI
	 */
	protected $parent_gui;
	/**
	 * @var ilUserSetting
	 */
	protected $object;


	/**
	 * @param ilUserSettingsGUI $parent_gui
	 * @param ilUserSetting $ilUserSetting
	 */
	public function __construct(ilUserSettingsGUI $parent_gui, ilUserSetting $ilUserSetting) {

		global $ilCtrl;
		$this->parent_gui = $parent_gui;
		$this->object = $ilUserSetting;
		$this->ctrl = $ilCtrl;
		$this->pl = ilUserDefaultsPlugin::getInstance();
		//		$this->pl->updateLanguageFiles();
		$this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
		$this->initForm();
	}


	/**
	 * @param $key
	 *
	 * @return string
	 */
	protected function txt($key) {
		return $this->pl->txt('set_' . $key);
	}


	protected function initForm() {
		$this->setTitle($this->pl->txt('form_title'));
		$te = new ilTextInputGUI($this->txt(self::F_TITLE), self::F_TITLE);
		$te->setRequired(true);
		$this->addItem($te);

		$this->setTitle($this->pl->txt('form_title'));
		$te = new ilTextAreaInputGUI($this->txt(self::F_DESCRIPTION), self::F_DESCRIPTION);
		$this->addItem($te);


		$se = new ilSelectInputGUI($this->txt(self::F_GLOBAL_ROLE), self::F_GLOBAL_ROLE);
		$se->setRequired(true);
		$global_roles = array("" => $this->txt("form_please_choose"));
		$global_roles = array_merge($global_roles, self::getRoles(ilRbacReview::FILTER_ALL_GLOBAL));
		$se->setOptions($global_roles);
		$this->addItem($se);

		/// Assigned Courses
		$multiSelect = new udfMultiLineInputGUI($this->txt(self::F_PORTFOLIO_ASSIGNED_TO_GROUPS), "MultiGroup");
		$multiSelect->setShowLabel(true);


		$ilCourseMultiSelectInputGUI = new ilContainerMultiSelectInputGUI('crs', $this->txt(self::F_ASSIGNED_COURSES), self::F_ASSIGNED_COURSES);
		$ilCourseMultiSelectInputGUI->setAjaxLink($this->ctrl->getLinkTarget($this->parent_gui, ilUserSettingsGUI::CMD_SEARCH_COURSES));
		$this->addItem($ilCourseMultiSelectInputGUI);

		$ilCourseMultiSelectInputGUI = new ilContainerMultiSelectInputGUI('crs', $this->txt(self::F_ASSIGNED_COURSES_DESKTOP), self::F_ASSIGNED_COURSES_DESKTOP);
		$ilCourseMultiSelectInputGUI->setAjaxLink($this->ctrl->getLinkTarget($this->parent_gui, ilUserSettingsGUI::CMD_SEARCH_COURSES));
		$this->addItem($ilCourseMultiSelectInputGUI);

		$ilCourseMultiSelectInputGUI = new ilContainerMultiSelectInputGUI('grp', $this->txt(self::F_ASSIGNED_GROUPS), self::F_ASSIGNED_GROUPS);
		$ilCourseMultiSelectInputGUI->setAjaxLink($this->ctrl->getLinkTarget($this->parent_gui, ilUserSettingsGUI::CMD_SEARCH_COURSES));
		$this->addItem($ilCourseMultiSelectInputGUI);

		$ilCourseMultiSelectInputGUI = new ilContainerMultiSelectInputGUI('grp', $this->txt(self::F_ASSIGNED_GROUPS_DESKTOP), self::F_ASSIGNED_GROUPS_DESKTOP);
		$ilCourseMultiSelectInputGUI->setAjaxLink($this->ctrl->getLinkTarget($this->parent_gui, ilUserSettingsGUI::CMD_SEARCH_COURSES));
		$this->addItem($ilCourseMultiSelectInputGUI);

		$se = new ilSelectInputGUI($this->txt(self::F_PORTFOLIO_TEMPLATE_ID), self::F_PORTFOLIO_TEMPLATE_ID);

		$options = ilObjPortfolioTemplate::getAvailablePortfolioTemplates();
		//		$options[0] = $this->pl->txt('crs_no_template');
		$options[1] = '--';

		asort($options);

		$se->setOptions($options);
		$this->addItem($se);

		$te = new ilTextInputGUI($this->txt(self::F_PORTFOLIO_NAME), self::F_PORTFOLIO_NAME);
		$te->setInfo(ilUserSetting::getAvailablePlaceholdersAsString());
		//		$te->setRequired(true);
		$this->addItem($te);

		$te = new ilTextInputGUI($this->txt(self::F_BLOG_NAME), self::F_BLOG_NAME);
		$this->addItem($te);

		$ilCourseMultiSelectInputGUI = new ilContainerMultiSelectInputGUI('grp', $this->txt(self::F_PORTFOLIO_ASSIGNED_TO_GROUPS), self::F_PORTFOLIO_ASSIGNED_TO_GROUPS);
		$ilCourseMultiSelectInputGUI->setAjaxLink($this->ctrl->getLinkTarget($this->parent_gui, ilUserSettingsGUI::CMD_SEARCH_COURSES));
		$this->addItem($ilCourseMultiSelectInputGUI);

		$ilOrgUnitMultiSelectInputGUI = new ilContainerMultiSelectInputGUI('orgu', $this->txt(self::F_ASSIGNED_ORGUS), self::F_ASSIGNED_ORGUS);
		$ilOrgUnitMultiSelectInputGUI->setAjaxLink($this->ctrl->getLinkTarget($this->parent_gui, ilUserSettingsGUI::CMD_SEARCH_COURSES));
		$this->addItem($ilOrgUnitMultiSelectInputGUI);

		if ($this->pl->is51()) {
			$ilStudyProgramMultiSelectInputGUI = new ilContainerMultiSelectInputGUI('prg', $this->txt(self::F_ASSIGNED_STUDYPROGRAMS), self::F_ASSIGNED_STUDYPROGRAMS);
			$ilStudyProgramMultiSelectInputGUI->setAjaxLink($this->ctrl->getLinkTarget($this->parent_gui, ilUserSettingsGUI::CMD_SEARCH_COURSES));
			$this->addItem($ilStudyProgramMultiSelectInputGUI);
		}

		$this->addCommandButtons();
	}


	/**
	 * @param int $filter
	 * @param bool $with_text
	 *
	 * @return array
	 */
	public static function getRoles($filter, $with_text = true) {
		global $rbacreview;
		$opt = array();
		$role_ids = array();
		foreach ($rbacreview->getRolesByFilter($filter) as $role) {
			$opt[$role['obj_id']] = $role[self::F_TITLE] . ' (' . $role['obj_id'] . ')';
			$role_ids[] = $role['obj_id'];
		}
		if ($with_text) {
			return $opt;
		} else {
			return $role_ids;
		}
	}


	public function fillForm() {
		$array = array(
			self::F_TITLE                        => $this->object->getTitle(),
			self::F_DESCRIPTION                  => $this->object->getDescription(),
			//			self::F_STATUS => ($this->object->getStatus() == ilUserSetting::STATUS_ACTIVE ? 1 : 0),
			self::F_ASSIGNED_COURSES             => implode(',', $this->object->getAssignedCourses()),
			self::F_ASSIGNED_COURSES_DESKTOP	 => implode(',', $this->object->getAssignedCoursesDesktop()),
			self::F_ASSIGNED_GROUPS              => implode(',', $this->object->getAssignedGroupes()),
			self::F_ASSIGNED_GROUPS_DESKTOP      => implode(',', $this->object->getAssignedGroupesDesktop()),
			self::F_GLOBAL_ROLE                  => $this->object->getGlobalRole(),
			self::F_PORTFOLIO_TEMPLATE_ID        => $this->object->getPortfolioTemplateId(),
			self::F_PORTFOLIO_ASSIGNED_TO_GROUPS => implode(',', $this->object->getPortfolioAssignedToGroups()),
			self::F_BLOG_NAME                    => $this->object->getBlogName(),
			self::F_PORTFOLIO_NAME               => $this->object->getPortfolioName(),
			self::F_ASSIGNED_ORGUS               => implode(',', $this->object->getAssignedOrgus()),
			self::F_ASSIGNED_STUDYPROGRAMS       => implode(',', $this->object->getAssignedStudyprograms()),

		);
		$this->setValuesByArray($array);
	}


	/**
	 * @return bool
	 */
	public function saveObject() {
		if (!$this->checkInput()) {
			return false;
		}
		$this->object->setTitle($this->getInput(self::F_TITLE));
		$this->object->setDescription($this->getInput(self::F_DESCRIPTION));
		//		$this->object->setStatus($this->getInput(self::F_STATUS));
		$assigned_courses = $this->getInput(self::F_ASSIGNED_COURSES);
		$this->object->setAssignedCourses(explode(',', $assigned_courses[0]));
		$assigned_courses_desktop = $this->getInput(self::F_ASSIGNED_COURSES_DESKTOP);
		$this->object->setAssignedCoursesDesktop(explode(',', $assigned_courses_desktop[0]));
		$assigned_groups = $this->getInput(self::F_ASSIGNED_GROUPS);
		$this->object->setAssignedGroupes(explode(',', $assigned_groups[0]));
		$assigned_groups_desktop = $this->getInput(self::F_ASSIGNED_GROUPS_DESKTOP);
		$this->object->setAssignedGroupesDesktop(explode(',', $assigned_groups_desktop[0]));
		$this->object->setGlobalRole($this->getInput(self::F_GLOBAL_ROLE));
		$portfolio_template_id = $this->getInput(self::F_PORTFOLIO_TEMPLATE_ID);
		$this->object->setPortfolioTemplateId($portfolio_template_id > 0 ? $portfolio_template_id : null);
		$portf_assigned_to_groups = $this->getInput(self::F_PORTFOLIO_ASSIGNED_TO_GROUPS);
		$this->object->setPortfolioAssignedToGroups(explode(',', $portf_assigned_to_groups[0]));
		$this->object->setBlogName($this->getInput(self::F_BLOG_NAME));
		$this->object->setPortfolioName($this->getInput(self::F_PORTFOLIO_NAME));
		$assigned_orgus = $this->getInput(self::F_ASSIGNED_ORGUS);
		$this->object->setAssignedOrgus(explode(',', $assigned_orgus[0]));
		$assigned_studyprograms = $this->getInput(self::F_ASSIGNED_STUDYPROGRAMS);
		$this->object->setAssignedStudyprograms(explode(',', $assigned_studyprograms[0]));

		if ($this->object->getId() > 0) {
			$this->object->update();
		} else {
			$this->object->create();
		}

		return true;
	}


	protected function addCommandButtons() {
		if ($this->object->getId() > 0) {
			$this->addCommandButton(ilUserSettingsGUI::CMD_UPDATE, $this->pl->txt('form_button_update'));
		} else {
			$this->addCommandButton(ilUserSettingsGUI::CMD_CREATE, $this->pl->txt('form_button_create'));
		}
		$this->addCommandButton(ilUserSettingsGUI::CMD_CANCEL, $this->pl->txt('form_button_cancel'));
	}
}

