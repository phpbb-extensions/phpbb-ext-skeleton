<?xml version="1.0" encoding="UTF-8"?>
<project name="Skeleton Extension Builder" description="Builds an extension.zip from a git repository" default="all">
	<property name="vendor-name" value="{EXTENSION.vendor_name}" />
	<property name="extension-name" value="{EXTENSION.extension_name}" />
	<!--
	Only set this to "true" if you have dependencies in the composer.json,
	otherwise use "false".
	-->
	<property name="has-dependencies" value="false" />

	<target name="clean-package">
		<!--
		Remove some unnecessary files/directories
		${extension-dir}/ is the folder of your extension, e.g. ext/acme/demo/
		-->
		<delete dir="${extension-dir}/tests" />
		<delete dir="${extension-dir}/travis" />

		<delete file="${extension-dir}/.gitignore" />
		<delete file="${extension-dir}/.gitattributes" />
		<delete file="${extension-dir}/.travis.yml" />
		<delete file="${extension-dir}/build.xml" />
		<delete file="${extension-dir}/composer.lock" />
		<delete file="${extension-dir}/composer.phar" />
		<delete file="${extension-dir}/phpunit.xml.dist" />
		<delete file="${extension-dir}/README.md" />
	</target>

	<!--
	TODO: DO NOT EDIT BELOW THIS LINE!!!!
	-->

	<property name="build-version" value="HEAD" override="true" />
	<property name="build-directory" value="build" override="true" />
	<property name="package-directory" value="${build-directory}/package/${vendor-name}/${extension-name}" />

	<!-- These are the main targets which you will probably want to use -->
	<target name="all" depends="prepare-structure,package" />

	<!--
	Clean up the build directory
	-->
	<target name="clean">
		<delete dir="${build-directory}" />
	</target>

	<!--
	Recreate the necessary folders
	-->
	<target name="prepare-structure" depends="clean">
		<mkdir dir="${build-directory}" />
		<mkdir dir="${build-directory}/checkout" />
		<mkdir dir="${build-directory}/package" />
		<mkdir dir="${build-directory}/package/${vendor-name}" />
		<mkdir dir="${build-directory}/package/${vendor-name}/${extension-name}" />
		<mkdir dir="${build-directory}/upload" />
	</target>

	<!--
	The real packaging
	-->
	<target name="package">
		<echo msg="Extracting ${build-version}" />

		<phingcall target="git-checkout">
			<property name="archive-version" value="${build-version}" />
		</phingcall>

		<if>
			<equals arg1="${has-dependencies}" arg2="1" />
			<then>
				<exec dir="${package-directory}" command="php composer.phar install --no-dev"
					  checkreturn="true" />
			</then>
		</if>

		<phingcall target="clean-package">
			<property name="extension-dir" value="${package-directory}" />
		</phingcall>

		<!-- Try setting the package version property from composer.json -->
		<exec dir="${package-directory}"
			  command='php -r "\$j = json_decode(file_get_contents(\"composer.json\")); echo (isset(\$j->version) ? \$j->version : \"${build-version}\");"'
			  checkreturn="true"
			  outputProperty='package-version' />

		<phingcall target="wrap-package">
			<property name="destination-filename" value="${build-directory}/upload/${vendor-name}_${extension-name}_${package-version}" />
		</phingcall>
	</target>

	<!--
	Checkout a given version and install/clean the dependencies
	-->
	<target name="git-checkout">
		<echo msg="Getting archive for ${archive-version}" />

		<exec command="git archive ${archive-version} --format zip --output ${build-directory}/checkout/${archive-version}.zip"
			  checkreturn="true" />
		<unzip file="${build-directory}/checkout/${archive-version}.zip" todir="${package-directory}" />
	</target>

	<!--
	Create the zip and tar ball
	-->
	<target name="wrap-package">
		<echo msg="Creating archives (${vendor-name}/${extension-name} ${build-version})" />
		<zip basedir="${build-directory}/package/" destfile="${destination-filename}.zip" />
	</target>
</project>
